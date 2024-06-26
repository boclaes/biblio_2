<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use App\Helpers\BookHelper;
use App\Models\Book;
use App\Models\Review;
use App\Models\RejectedBook;
use App\Models\Borrowing;
use App\Models\AcceptedBook;
use Illuminate\Support\Facades\Session;

class BookController extends Controller
{
    protected $bookHelper;

    public function __construct(BookHelper $bookHelper)
    {
        $this->bookHelper = $bookHelper;
    }

    public function home()
    {
        return view('home');
    }

    public function index()
    {
        return view('index');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $searchType = $request->input('searchType', 'title');
    
        // Validate search type
        if (!in_array($searchType, ['isbn', 'title'])) {
            return redirect()->route('search')->with('error', 'Invalid search type specified.');
        }
    
        if ($searchType === 'isbn') {
            return $this->searchByISBN($query);
        } else {
            return $this->searchByTitle($query);
        }
    }    

    private function searchByISBN($isbn)
    {
        Log::info("Searching for book with ISBN: {$isbn}");
        $bookDetails = $this->bookHelper->getBookDetailsByISBN($isbn);
    
        if (!$bookDetails) {
            Log::error("Book not found or API request failed for ISBN: {$isbn}");
            return redirect()->route('books')->with('error', 'Book not found or API request failed.');
        }
    
        if (!isset($bookDetails['title'])) {
            Log::error("Book details are incomplete. Title is missing for ISBN: {$isbn}");
            return redirect()->route('books')->with('error', 'Book details are incomplete. Title is missing.');
        }
    
        try {
            $user = Auth::user();
            $existingBook = $user->books()
                ->where('title', $bookDetails['title'])
                ->where('google_books_id', $bookDetails['google_books_id'])
                ->first();
    
            if ($existingBook) {
                Log::info("Book already in collection: {$bookDetails['title']}");
                return redirect()->route('books')->with('error', 'This book is already in your collection.');
            }
    
            $book = Book::create($bookDetails);
            $user->books()->attach($book);
    
            Log::info("Book saved to library: {$bookDetails['title']}");
            return redirect()->route('books')->with('success', 'Book saved to your library.');
        } catch (\Exception $exception) {
            Log::error("Failed to add book to collection: " . $exception->getMessage());
            return redirect()->route('search.form')->with('error', 'Failed to add book to your collection.');
        }
    }
    
    private function searchByTitle($title)
    {
        if (empty($title)) {
            Log::error("Search query is empty for title search.");
            return redirect()->route('search')->with('error', 'Search query cannot be empty.');
        }
    
        Log::info("Searching for books with title: {$title}");
        $books = $this->bookHelper->searchBooksByTitle($title);
    
        if (empty($books)) {
            return redirect()->route('search.form')->with('error', 'No books found for the given title.');
        }
    
        $books = array_slice($books, 0, 5);
    
        $user = Auth::user();
        $acceptedBooks = $user->acceptedBooks()->get();
        $userBooks = $user->books()->get(); // Ensure user books are also retrieved
    
        $acceptedBookMap = $acceptedBooks->pluck('id', 'google_books_id')->toArray();
        $userBookMap = $userBooks->pluck('id', 'google_books_id')->toArray(); // Map for user books
    
        // Check the value of wish parameter
        if (request()->has('wish') && request()->input('wish') == 1) {
            return view('selectWish', [
                'books' => $books,
                'acceptedBookMap' => $acceptedBookMap,
                'userBookMap' => $userBookMap, // Pass the userBookMap to the view
                'query' => $title,
                'wish' => 1 // Pass the wish parameter to the view
            ]);
        } else {
            return view('selectBook', [
                'books' => $books,
                'acceptedBookMap' => $acceptedBookMap,
                'userBookMap' => $userBookMap, // Pass the userBookMap to the view
                'query' => $title,
            ]);
        }
    }    

    public function addBookWish(Request $request)
    {
        Log::info('Received data for adding book to wishlist:', $request->all());
    
        $bookId = $request->input('bookId');
        $query = $request->input('query');
        $searchType = $request->input('searchType', 'isbn');
        $wish = $request->input('wish', 0); // Get the wish parameter, default to 0
    
        $bookDetails = $this->bookHelper->getBookDetailsById($bookId);
    
        if (!$bookDetails) {
            Log::error('Book details not found for ID: ' . $bookId);
            return redirect()->route('search.form')->with('error', 'Failed to fetch book details.');
        }
    
        Log::info('Book details retrieved:', $bookDetails);
    
        try {
            $user = Auth::user();
            $existingBook = $user->acceptedBooks()
                ->where('title', $bookDetails['title'])
                ->where('google_books_id', $bookDetails['google_books_id'])
                ->first();
    
            if ($existingBook) {
                Log::warning('Attempt to add duplicate book to wishlist: ' . $bookDetails['title'] . ' (Google Books ID: ' . $bookDetails['google_books_id'] . ')');
                return redirect()->route('search.form')->with('error', 'This book is already in your wishlist.');
            }
    
            // Add the generated purchase link to the book details
            $bookDetails['purchase_link'] = $this->generatePurchaseLink($bookDetails['title'], $bookDetails['author']);
    
            $acceptedBook = new AcceptedBook($bookDetails);
            $acceptedBook->user_id = $user->id;
            $acceptedBook->save();
    
            Log::info('Book added to wishlist: ' . $bookDetails['title']);
    
            // Redirect based on the wish parameter
            if ($wish == 1) {
                return redirect()->route('search', ['query' => $query, 'wish' => 1])->with('success', 'Book added to your wishlist successfully.');
            } else {
                return redirect()->route('search', ['query' => $query])->with('success', 'Book added to your wishlist successfully.');
            }
        } catch (\Exception $e) {
            Log::error('Failed to add book to wishlist: ' . $e->getMessage());
            return redirect()->route('search')->with('error', 'Failed to add book to your wishlist.');
        }
    }

    public function addBook(Request $request)
    {
        Log::info('Received data for adding book:', $request->all());

        $bookId = $request->input('bookId');
        $query = $request->input('query');
        $searchType = $request->input('searchType', 'isbn');

        $bookDetails = $this->bookHelper->getBookDetailsById($bookId);

        if (!$bookDetails) {
            Log::error('Book details not found for ID: ' . $bookId);
            return redirect()->route('search.form')->with('error', 'Failed to fetch book details.');
        }

        Log::info('Book details retrieved:', $bookDetails);

        try {
            $user = Auth::user();
            $existingBook = $user->books()
            ->where('title', $bookDetails['title'])
            ->where('google_books_id', $bookDetails['google_books_id'])
            ->first();

            if ($existingBook) {
                Log::warning('Attempt to add duplicate book: ' . $bookDetails['title'] . ' (Google Books ID: ' . $bookDetails['google_books_id'] . ')');
                return redirect()->route('search.form')->with('error', 'This book is already in your collection.');
            }

            $book = new Book($bookDetails);
            $book->save();
            $user->books()->attach($book);

            if ($searchType === 'title') {
                return redirect()->route('search', ['query' => $query])->with('success', 'Book added to your library successfully.');
            } else {
                return redirect()->route('search')->with('success', 'Book added to your library successfully.');
            }
        } catch (\Exception $e) {
            Log::error('Failed to add book: ' . $e->getMessage());
            return redirect()->route('search.form')->with('error', 'Failed to add book to your collection.');
        }
    }

    public function list()
    {
        $user = Auth::user();
        $books = $user->books()->with('reviews')->get();

        return view('books', compact('books'));
    }

    public function delete(Request $request, $id)
    {
        $user = Auth::user();
        $book = $user->books()->find($id);

        if (!$book) {
            return redirect()->route('books')->with('error', 'You do not have permission to delete this book.');
        }

        try {
            $book->delete();
            $user->books()->detach($id);

            $query = $request->input('query');
            if ($query) {
                return redirect()->route('search', ['query' => $query])->with('success', 'Book deleted successfully.');
            }

            return redirect()->back()->with('success', 'Book deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete book: ' . $e->getMessage());
            return redirect()->route('books')->with('error', 'Failed to delete the book.');
        }
    }

    public function editBook(Request $request, $id)
    {
        $user = Auth::user();
        $book = $user->books()->find($id);

        if (!$book) {
            return redirect()->route('books')->with('error', 'You do not have permission to edit this book.');
        }

        $query = $request->input('query');
        return view('edit_book', compact('book', 'query'));
    }

    public function addEditBook(Request $request, $id)
    {
        $user = Auth::user();
        $book = $user->books()->find($id);

        if (!$book) {
            return redirect()->route('books')->with('error', 'You do not have permission to edit this book.');
        }

        $query = $request->input('query');
        return view('add_edit_book', compact('book', 'query'));
    }

    public function updateBook(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'pages' => 'required|integer|min:1',
            'place' => 'required|integer|min:0',
            'description' => 'required|string',
        ]);
    
        $user = Auth::user();
        $book = $user->books()->find($id);
    
        if (!$book) {
            return redirect()->route('books')->with('error', 'You do not have permission to edit this book.');
        }
    
        try {
            $book->title = $request->input('title');
            $book->author = $request->input('author');
            $book->pages = $request->input('pages');
            $book->place = $request->input('place');
            $book->description = $request->input('description');
            $book->save();
    
            $query = $request->input('query');
            if ($query) {
                return redirect()->route('search', ['query' => $query])->with('success', 'Book details updated successfully.');
            }
    
            return redirect()->route('details.book', $book->id)->with('success', 'Book details updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update book: ' . $e->getMessage());
            return redirect()->route('books')->with('error', 'Failed to update the book.');
        }
    }

    public function saveNotes(Request $request, $id)
    {
        $user = Auth::user();
        $book = $user->books()->find($id);

        if (!$book) {
            return redirect()->route('books')->with('error', 'You do not have permission to edit this book.');
        }

        try {
            $book->notes_user = $request->input('notes');
            $book->save();
            return redirect()->route('details.book', $book->id)->with('success', 'Notes saved successfully.');
        } catch (\Exception $e) {
            logger()->error('Error saving notes: ' . $e->getMessage());
            
            return back()->withInput()->withErrors(['error' => 'Failed to save notes. Please try again later.']);
        }
    }

    public function saveReview(Request $request, $id)
    {
        $user = Auth::user();
        $book = $user->books()->find($id);

        if (!$book) {
            return redirect()->route('books')->with('error', 'You do not have permission to edit this book.');
        }

        try {
            $book->review = $request->input('review');
            $book->save();
            return redirect()->route('details.book', $book->id)->with('success', 'Review saved successfully.');
        } catch (\Exception $e) {
            logger()->error('Error saving review: ' . $e->getMessage());
            
            return back()->withInput()->withErrors(['error' => 'Failed to save review. Please try again later.']);
        }
    }

    public function showDetails($id)
    {
        $user = Auth::user();
        $book = $user->books()->find($id);

        if (!$book) {
            return redirect()->route('books')->with('error', 'You do not have permission to view this book.');
        }

        return view('details', compact('book'));
    }

    public function showDetailsSearch($id) {
        $user = Auth::user();
        $book = $user->books()->find($id);
    
        if (!$book) {
            return redirect()->route('books')->with('error', 'You do not have permission to view this book.');
        }
    
        $query = request('query', '');
        return view('detailsSearch', ['book' => $book, 'query' => $query]);
    }

    public function editNotes($id)
    {
        $user = Auth::user();
        $book = $user->books()->find($id);

        if (!$book) {
            return redirect()->route('books')->with('error', 'You do not have permission to edit this book.');
        }

        return view('edit_notes', compact('book'));
    }

    public function editReview($id)
    {
        $user = Auth::user();
        $book = $user->books()->find($id);
    
        if (!$book) {
            return redirect()->route('books')->with('error', 'You do not have permission to edit this book.');
        }
    
        // Ensure the review field is being passed correctly
        $review = $book->review;
    
        return view('edit_review', compact('book', 'review'));
    }

    public function rateBook(Request $request, $bookId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();
        $book = $user->books()->find($bookId);

        if (!$book) {
            return response()->json(['error' => 'You do not have permission to rate this book.'], 403);
        }
    
        $logData = [
            'rating' => $request->rating,
            'user_id' => $user->id,
            'book_id' => $bookId,
            'timestamp' => now()->toDateTimeString(),
        ];
    
        $logFile = storage_path('logs/custom.log');
        file_put_contents($logFile, json_encode($logData) . PHP_EOL, FILE_APPEND);
    
        try {
            $review = Review::updateOrCreate(
                ['user_id' => $user->id, 'book_id' => $bookId],
                ['rating' => $request->rating]
            );
    
            return response()->json(['message' => 'Book rated successfully', 'review' => $review], 200);
        } catch (\Exception $e) {
            $errorLogData = [
                'error' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString(),
            ];
            file_put_contents($logFile, json_encode($errorLogData) . PHP_EOL, FILE_APPEND);
    
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function getBookRating($id)
    {
        $book = Book::findOrFail($id);

        $rating = $book->reviews()->avg('rating');

        return response()->json(['rating' => $rating]);
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $book = $user->books()->find($id);

        if (!$book) {
            return response()->json(['error' => 'You do not have permission to update this book.'], 403);
        }

        Log::info('Received status update data:', $request->all());
        
        try {
            $fields = ['want_to_read', 'reading', 'done_reading'];
            foreach ($fields as $field) {
                if ($request->has($field)) {
                    $book->$field = $request->$field;
                }
            }
    
            $book->save();
            
            return response()->json(['message' => 'Status updated successfully', 'book' => $book], 200);
        } catch (\Exception $e) {
            Log::error('Failed to update status: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update the status.'], 500);
        }
    }

    protected function calculateTopGenre($userId)
    {
        $user = Auth::user();
        $books = $user->books()->whereNotNull('genre')->get();
    
        if ($books->isEmpty()) {
            return null;
        }
    
        $genreCounts = [];
    
        foreach ($books as $book) {
            $genres = explode(' / ', $book->genre);
            Log::info("Processing book ID: {$book->id}, genres: " . json_encode($genres));
    
            // Filter out "General" from the genres
            $genres = array_filter($genres, function($genre) {
                return trim($genre) !== 'General';
            });
    
            // Check if it is a single genre and skip if it is just "Fiction"
            if (count($genres) === 1 && trim($genres[0]) === 'Fiction') {
                Log::info("Skipping genre '" . $genres[0] . "'");
                continue;
            }
    
            // Combine genres and count
            $combinedGenre = implode(' / ', array_map('trim', $genres));
            if (!isset($genreCounts[$combinedGenre])) {
                $genreCounts[$combinedGenre] = 0;
            }
            $genreCounts[$combinedGenre]++;
            Log::info("Genre '{$combinedGenre}' count incremented to: {$genreCounts[$combinedGenre]}");
        }
    
        Log::info("Final genre counts: " . json_encode($genreCounts));
    
        // Sort genres by count in descending order
        arsort($genreCounts);
    
        // Check for ties
        $topCount = reset($genreCounts);
        $topGenres = array_keys(array_filter($genreCounts, function($count) use ($topCount) {
            return $count === $topCount;
        }));
    
        // If there is a tie, pick a random genre from the top genres
        if (count($topGenres) > 1) {
            $selectedGenre = $topGenres[array_rand($topGenres)];
        } else {
            $selectedGenre = array_key_first($genreCounts);
        }
    
        Log::info("Top genre being queried: {$selectedGenre}");
    
        return $selectedGenre;
    }

    public function recommendBook()
    {
        $user = Auth::user();
        $exclusionList = $this->getExclusionList($user->id);
    
        // Recalculate the top genre on each request
        $topGenre = $this->calculateTopGenre($user->id);
        if (!$topGenre) {
            return redirect()->route('books')->with('error', 'No favorite genres found. Add some books to get recommendations!');
        }
        Session::put('current_genre', $topGenre);
    
        Log::info("Top genre being queried: " . $topGenre);
        $startIndex = 0;
        $books = $this->bookHelper->getRecommendations([$topGenre], $exclusionList, $user->id, $startIndex, 4);
    
        if (!$books) {
            // Recalculate the top genre if no books are found
            $topGenre = $this->calculateTopGenre($user->id);
            if (!$topGenre) {
                return redirect()->route('books')->with('error', 'No favorite genres found. Add some books to get recommendations!');
            }
            Session::put('current_genre', $topGenre);
    
            $books = $this->bookHelper->getRecommendations([$topGenre], $exclusionList, $user->id, $startIndex, 4);
        }
    
        if (!$books) {
            return redirect()->route('books')->with('error', 'No recommendations found for your favorite genres.');
        }
    
        return view('recommendation', compact('books'));
    }

    public function handleDecision(Request $request)
    {
        try {
            $decision = $request->input('decision');
            $bookDetails = $request->only(['google_books_id', 'title', 'author', 'year', 'description', 'cover', 'genre', 'pages']);
    
            $user = Auth::user();
            $userId = $user->id;
    
            if ($decision === 'reject') {
                $this->rejectBook($userId, $bookDetails);
            } elseif ($decision === 'accept') {
                $this->acceptBook($userId, $bookDetails);
            }
    
            $exclusionList = $this->getExclusionList($userId);
            $topGenre = Session::get('current_genre');
    
            if (!$topGenre) {
                return response()->json(['error' => 'No favorite genres found'], 400);
            }
    
            $startIndex = $user->last_book_index + 1;
            Log::info("handleDecision updated startIndex to: {$startIndex}");
    
            $newBook = $this->bookHelper->getRecommendations([$topGenre], $exclusionList, $userId, $startIndex, 1);
    
            // Retry with recalculated top genre if no books are found
            if (!$newBook) {
                Log::info("No recommendations found, recalculating top genre and retrying...");
                $topGenre = $this->calculateTopGenre($userId);
                if (!$topGenre) {
                    return response()->json(['error' => 'No favorite genres found'], 400);
                }
                Session::put('current_genre', $topGenre);
    
                $newBook = $this->bookHelper->getRecommendations([$topGenre], $exclusionList, $userId, $startIndex, 1);
            }
    
            $newBook = $newBook[0] ?? null;  // Ensure we're only taking the first book
    
            return response()->json([
                'success' => true,
                'newBook' => $newBook
            ]);
        } catch (\Exception $e) {
            Log::error("Error handling decision: " . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }    

    public function rejectBook($userId, $bookDetails)
    {
        Log::info('Rejecting book: ' . $bookDetails['title'] . ' by ' . $bookDetails['author']);
        if (empty($bookDetails['google_books_id'])) {
            return response()->json(['error' => 'Google Books ID is required'], 400);
        }
    
        RejectedBook::create([
            'user_id' => $userId,
            'google_books_id' => $bookDetails['google_books_id'],
            'title' => $bookDetails['title'],
            'author' => $bookDetails['author'],
            'year' => $bookDetails['year'],
        ]);
    
        $user = Auth::user();
        $exclusionList = $this->getExclusionList($userId);
        $topGenre = $this->calculateTopGenre($userId);
    
        if (!$topGenre) {
            return response()->json(['error' => 'No favorite genres found'], 400);
        }
    
        $startIndex = $user->last_book_index + 1;
        Log::info("rejectBook updated startIndex to: {$startIndex}");
    
        $newBook = $this->bookHelper->getRecommendations([$topGenre], $exclusionList, $userId, $startIndex);
    
        return response()->json([
            'success' => true,
            'newBook' => $newBook
        ]);
    }

    protected function getExclusionList($userId)
    {
        $rejectedBooks = RejectedBook::where('user_id', $userId)->get(['title', 'author', 'year']);
        $acceptedBooks = AcceptedBook::where('user_id', $userId)->get(['title', 'author', 'year']);
        $libraryBooks = Book::whereHas('users', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get(['title', 'author', 'year']);
        
        $excludedBooks = $rejectedBooks->concat($acceptedBooks)->concat($libraryBooks);
    
        $exclusionList = $excludedBooks->map(function ($book) {
            $title = strtolower(trim($book->title));
            $author = strtolower(trim($book->author));
            $year = substr(trim($book->year), 0, 4); // Assuming year is in format YYYY-MM-DD or similar
            $key = "{$title}|{$author}|{$year}";
            Log::info("Adding to exclusion list: {$key}");
            return $key;
        })->toArray();
    
        Log::info("Complete exclusion list: " . json_encode($exclusionList));
        return $exclusionList;
    }

    public function acceptBook($userId, $bookDetails)
    {
        // Check if the book is already in the accepted books
        $existingBook = AcceptedBook::where([
            ['user_id', '=', $userId],
            ['google_books_id', '=', $bookDetails['google_books_id']]
        ])->first();
    
        if ($existingBook) {
            // Book already exists in the accepted books, no need to add it again
            return;
        }
    
        $defaultPurchaseLink = $this->generatePurchaseLink($bookDetails['title'], $bookDetails['author']);
    
        AcceptedBook::create([
            'user_id' => $userId,
            'google_books_id' => $bookDetails['google_books_id'],
            'title' => $bookDetails['title'],
            'author' => $bookDetails['author'],
            'year' => $bookDetails['year'],
            'description' => $bookDetails['description'],
            'cover' => $bookDetails['cover'],
            'genre' => $bookDetails['genre'],
            'pages' => $bookDetails['pages'],
            'purchase_link' => $bookDetails['purchase_link'] ?? $defaultPurchaseLink
        ]);
    }

    private function generatePurchaseLink($title, $author)
    {
        $baseURL = "https://www.amazon.com/s?k=";
        $query = urlencode("\"$title\" \"$author\"");
        return $baseURL . $query;
    }

    public function showAcceptedBooks()
    {
        $user = Auth::user();
        $acceptedBooks = AcceptedBook::where('user_id', $user->id)->get();
        return view('acceptedBooks', compact('acceptedBooks'));
    }

    public function deleteAcceptedBook($id)
    {
        $user = Auth::user();
        $acceptedBook = $user->acceptedBooks()->findOrFail($id);

        if (!$acceptedBook) {
            return redirect()->route('books')->with('error', 'You do not have permission to delete this book.');
        }

        try {
            $acceptedBook->delete();
            return redirect()->back()->with('success', 'Accepted book deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete accepted book: ' . $e->getMessage());
            return redirect()->route('books')->with('error', 'Failed to delete the accepted book.');
        }
    }

    public function showAddBorrow()
    {
        $user = Auth::user();
        $books = $user->books()->where('borrowed', 0)->get();
        return view('add_borrow', compact('books'));
    }

    public function storeBorrow(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'borrower_name' => 'required|string',
            'borrowed_since' => 'required|date',
        ]);

        $user = Auth::user();
        $book = $user->books()->findOrFail($request->book_id);

        if (!$book) {
            return redirect()->route('borrowed-books')->with('error', 'You do not have permission to borrow this book.');
        }

        try {
            $book->borrowed = true;
            $book->save();
    
            $borrowing = new Borrowing([
                'book_id' => $request->book_id,
                'borrower_name' => $request->borrower_name,
                'borrowed_since' => $request->borrowed_since,
                'user_id' => $user->id,
            ]);
            $borrowing->save();
    
            return redirect()->route('borrowed-books')->with('success', 'Book borrowing recorded successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to record book borrowing: ' . $e->getMessage());
            return redirect()->route('borrowed-books')->with('error', 'Failed to record the book borrowing.');
        }
    }

    public function showBorrowedBooks()
    {
        $user = Auth::user();
        $borrowings = Borrowing::with('book')->where('user_id', $user->id)->get();
        return view('borrowed_books', compact('borrowings'));
    }

    public function returnBook(Borrowing $borrowing)
    {
        $user = Auth::user();

        if ($borrowing->user_id !== $user->id) {
            return redirect()->route('borrowed-books')->with('error', 'You do not have permission to return this book.');
        }

        try {
            $book = $borrowing->book;
            $book->borrowed = 0;
            $book->save();
            $borrowing->delete();
            return redirect()->route('borrowed-books')->with('success', 'Book returned successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to return book: ' . $e->getMessage());
            return redirect()->route('borrowed-books')->with('error', 'Failed to return the book.');
        }
    }

    public function searchForm()
    {
        return view('search'); // This now points to what was previously home.blade.php
    }

    public function searchWish()
    {
        return view('search_wish'); // This now points to what was previously home.blade.php
    }

    public function detailsBack(Request $request)
    {
        $query = $request->input('query', '');
        return redirect()->route('search', ['query' => $query]);
    }

    public function showEditBorrow($id)
    {
        $user = Auth::user();
        
        // Fetch the borrowing record
        $borrowing = Borrowing::where('id', $id)->where('user_id', $user->id)->first();
    
        if (!$borrowing) {
            // Redirect to the borrowed books page if the borrowing record is not found or doesn't belong to the user
            return redirect()->route('borrowed-books')->with('error', 'Borrowing record not found or you do not have permission to edit this record.');
        }
    
        $books = $user->books; // Assuming you want to show all books regardless of borrowing status here for editing
    
        return view('edit_borrow', compact('borrowing', 'books'));
    }

    public function updateBorrow(Request $request, Borrowing $borrowing)
    {
        $user = Auth::user();

        if ($borrowing->user_id !== $user->id) {
            return redirect()->route('borrowed-books')->with('error', 'You do not have permission to edit this borrowing record.');
        }

        $request->validate([
            'book_id' => 'required|exists:books,id',
            'borrower_name' => 'required|string',
            'borrowed_since' => 'required|date',
        ]);

        try {
            $borrowing->update([
                'book_id' => $request->book_id,
                'borrower_name' => $request->borrower_name,
                'borrowed_since' => $request->borrowed_since,
            ]);
    
            return redirect()->route('borrowed-books')->with('success', 'Borrowing details updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update borrowing details: ' . $e->getMessage());
            return redirect()->route('borrowed-books')->with('error', 'Failed to update the borrowing details.');
        }
    }
}
