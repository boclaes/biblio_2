<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Book;
use App\Models\User;


class BookHelper
{
    private function cleanDescription($description)
    {
        $description = strip_tags($description);
        $description = preg_replace('/\s+/', ' ', $description);
        $maxLength = 1000; // Adjust the length as needed

        if (strlen($description) > $maxLength) {
            $truncated = substr($description, 0, $maxLength);

            $lastPunctuation = max(
                strrpos($truncated, '.'),
                strrpos($truncated, '!'),
                strrpos($truncated, '?')
            );

            if ($lastPunctuation !== false && $lastPunctuation >= $maxLength - 50) {
                $description = substr($truncated, 0, $lastPunctuation + 1) . '...';
            } else {
                $lastSpace = strrpos($truncated, ' ');
                if ($lastSpace !== false && $lastSpace >= $maxLength - 50) {
                    $description = substr($truncated, 0, $lastSpace) . '...';
                } else {
                    $description = rtrim($truncated) . '...';
                }
            }
        }

        return $description;
    }

    private function isBookValid($book, $exclusionList)
    {
        $info = $book['volumeInfo'] ?? [];
        $title = strtolower(trim($info['title'] ?? ''));
        $authors = isset($info['authors']) ? implode(", ", $info['authors']) : '';
        $authors = strtolower(trim($authors));
        $publishedDate = substr($info['publishedDate'] ?? '', 0, 4);
        $bookKey = "{$title}|{$authors}|{$publishedDate}";

        Log::info("Checking book: {$bookKey}");
        return !in_array($bookKey, $exclusionList);
    }

    private function selectRelevantGenre($genres)
    {
        $preferredGenres = [
            'Fantasy', 'Science Fiction', 'Mystery', 'Thriller', 'Romance',
            'Young Adult (YA) Fiction', 'Historical Fiction', 'Horror',
            'Literary Fiction', 'Adventure', 'Non-Fiction', 'Biography',
            'Memoir', 'Self-Help', 'Health & Wellness', 'Children’s Literature',
            'Crime', 'Graphic Novel', 'Paranormal', 'Classics', 'Humor',
            'Western', 'Dystopian', 'Contemporary', 'Psychological Thriller',
            'Action and Adventure', 'Espionage', 'Urban Fantasy', 'Epic Fantasy',
            'Middle Grade', 'Picture Books', 'Erotica', 'True Crime', 'War & Military',
            'History', 'Philosophy', 'Poetry', 'Self-Improvement', 'Business',
            'Science & Technology', 'Cookbooks', 'Art & Photography', 'Religious & Spiritual',
            'Gardening & Horticulture', 'Sports', 'Travel', 'True Adventure',
            'Music', 'Fairy Tales', 'Folklore', 'Drama', 'Crafts & Hobbies',
            'Parenting & Families', 'Health & Fitness', 'Medical', 'Political',
            'Legal Thriller', 'Sociology', 'Anthropology', 'Cyberpunk', 'Steam Punk',
            'Historical Romance', 'Regency Romance', 'Inspirational', 'Alternative History',
            'Realistic Fiction'
        ];        

        if (is_string($genres)) {
            $genres = explode(' / ', $genres);
        }

        $normalizedGenres = array_map(function($genre) {
            $genre = str_replace('&', 'and', $genre);
            return trim($genre);
        }, $genres);

        Log::info('Normalized genres:', $normalizedGenres);

        $matchedGenres = array_intersect($normalizedGenres, $preferredGenres);
        if (!empty($matchedGenres)) {
            $selectedGenre = reset($matchedGenres);
            Log::info('Selected genre: ' . $selectedGenre);
            return $selectedGenre;
        }

        $fallbackGenre = !empty($normalizedGenres) ? reset($normalizedGenres) : 'Fiction';
        Log::info('Fallback genre: ' . $fallbackGenre);
        return $fallbackGenre;
    }     

    private function getGoogleImage($title)
    {
        $apiKey = config('GOOGLE_CSE_API_KEY', env('GOOGLE_CSE_API_KEY'));
        $cx = config('GOOGLE_CSE_CX', env('GOOGLE_CSE_CX'));
        
        Log::info("Google CSE API Key: " . $apiKey);
        Log::info("Google CSE CX: " . $cx);

        if (empty($apiKey) || empty($cx)) {
            Log::error("API key or CX is not set in the environment variables.");
            return asset('images/default_cover.jpg');
        }

        $query = urlencode($title . ' book cover');
        $url = "https://www.googleapis.com/customsearch/v1?q={$query}&cx={$cx}&key={$apiKey}&searchType=image&num=1";

        Log::info("Fetching Google Image with URL: {$url}");

        $response = Http::get($url);
        if ($response->successful()) {
            $data = $response->json();
            Log::info("Google Image Search Response: ", $data);

            if (!empty($data['items'][0]['link'])) {
                return $data['items'][0]['link'];
            }
        } else {
            Log::error("Failed to fetch Google Image: " . $response->body());
        }

        return asset('images/default_cover.jpg');
    }    

    private function formatBookDetails($bookItem)
    {
        $bookInfo = $bookItem['volumeInfo'] ?? [];
    
        if (empty($bookInfo['title'])) {
            Log::error("Title not found in book information: " . json_encode($bookInfo));
            return [
                'error' => 'Title not found in book information'
            ];
        }
    
        $title = $bookInfo['title'];
        $cover = $bookInfo['imageLinks']['thumbnail'] ?? $this->getGoogleImage($title);
        Log::info("Selected Cover Image for '{$title}': {$cover}");
    
        $publishedDate = isset($bookInfo['publishedDate']) ? $bookInfo['publishedDate'] : 'Unknown Year';
        $authors = implode(", ", $bookInfo['authors'] ?? ['Unknown Author']);
        $description = $this->cleanDescription($bookInfo['description'] ?? 'Description not available');
        $pages = isset($bookInfo['pageCount']) && $bookInfo['pageCount'] > 0 ? $bookInfo['pageCount'] : 'N/A';
        $genres = $bookInfo['categories'] ?? ['Classics'];
    
        return [
            'google_books_id' => $bookItem['id'] ?? null,
            'title' => $title,
            'author' => $authors,
            'year' => $publishedDate,
            'description' => $description,
            'cover' => $cover,
            'genre' => $this->selectRelevantGenre($genres),
            'pages' => $pages,
        ];
    }    

    public function getBookDetailsByISBN($isbn)
    {
        Log::info("Fetching book details for ISBN: {$isbn}");
        $response = Http::get("https://www.googleapis.com/books/v1/volumes?q=isbn:$isbn");
    
        if ($response->successful()) {
            $bookItem = $response->json('items.0');
            if ($bookItem) {
                Log::info("Book details retrieved successfully for ISBN: {$isbn}");
                return $this->formatBookDetails($bookItem);
            } else {
                Log::error("No book items found in API response for ISBN: {$isbn}");
            }
        } else {
            Log::error("Failed to fetch data from Google Books API for ISBN: {$isbn}. Response: " . $response->body());
        }
        return null;
    }
    

    public function searchBooksByTitle($title)
    {
        $response = Http::get("https://www.googleapis.com/books/v1/volumes?q=" . urlencode($title));
        if ($response->successful()) {
            return $response->json()['items'] ?? [];
        }
        return null;
    }

    public function getBookDetailsById($bookId)
    {
        $response = Http::get("https://www.googleapis.com/books/v1/volumes/{$bookId}");
        if ($response->successful()) {
            $bookItem = $response->json();
            return $this->formatBookDetails($bookItem);
        }
        return null;
    }

    public function getRecommendations(array $genres, $exclusionList, $userId, $startIndex = 0, $numBooks = 4)
    {
        $user = User::find($userId);
        $genreQuery = implode("|", array_map('urlencode', $genres));
        $version = Cache::get('books_cache_version', 1); // Retrieve current version or default to 1
        $cacheKey = "books_{$genreQuery}_{$startIndex}_{$version}"; // Use version in the cache key
    
        $books = Cache::get($cacheKey);
        $retryCount = 0;
        $retryLimit = 5; // Set the maximum number of retries to avoid infinite loops
    
        while (!$books && $retryCount < $retryLimit) {
            $query = "subject:{$genreQuery}&startIndex={$startIndex}&maxResults=40";
    
            // Fetch books sorted by relevance
            $fullUrlRelevance = "https://www.googleapis.com/books/v1/volumes?q={$query}&langRestrict=en&orderBy=relevance";
            Log::info("Querying Google Books API (Relevance): " . $fullUrlRelevance);
            $responseRelevance = Http::get($fullUrlRelevance);
    
            // Fetch books sorted by newest
            $fullUrlNewest = "https://www.googleapis.com/books/v1/volumes?q={$query}&langRestrict=en&orderBy=newest";
            Log::info("Querying Google Books API (Newest): " . $fullUrlNewest);
            $responseNewest = Http::get($fullUrlNewest);
    
            $booksRelevance = $responseRelevance->successful() ? $responseRelevance->json('items') : null;
            $booksNewest = $responseNewest->successful() ? $responseNewest->json('items') : null;
    
            // Recalculate the top genre and retry if both API responses are null
            if ($booksRelevance === null && $booksNewest === null) {
                Log::info("Both API responses are null, recalculating top genre and retrying...");
                $topGenre = $this->calculateTopGenre($userId);
                if (!$topGenre) {
                    Log::error("No favorite genres found after recalculating.");
                    return null;
                }
                $genres = [$topGenre];
                $genreQuery = implode("|", array_map('urlencode', $genres));
                $cacheKey = "books_{$genreQuery}_{$startIndex}_{$version}"; // Update cache key
                $retryCount++; // Increment the retry counter
                continue; // Continue to the next iteration of the loop
            }
    
            $mergedBooks = $this->mergeBooks($booksRelevance, $booksNewest);
    
            if (empty($mergedBooks)) {
                $attemptNumber = $retryCount + 1;
                Log::info("No more books available at startIndex {$startIndex}, retrying... (Attempt {$attemptNumber} of {$retryLimit})");
                $user->last_book_index = 0;
                $user->save();
    
                $newVersion = $version + 1;
                Cache::put('books_cache_version', $newVersion, 86400); // Store new version for a day
                Cache::forget($cacheKey); // Optionally clear the old cache immediately
    
                $startIndex = 0; // Reset startIndex for a new attempt
                $retryCount++; // Increment the retry counter
                continue; // Continue to the next iteration of the loop
            } else {
                Cache::put($cacheKey, $mergedBooks, 3600); // Cache the results for 1 hour
                $books = $mergedBooks;
                break; // Exit the loop as books have been found
            }
        }
    
        if ($retryCount >= $retryLimit) {
            Log::error("Maximum retries reached, no books found.");
            return null; // Exit the function if the retry limit is reached without success
        }
    
        $validBooks = [];
        foreach ($books as $index => $book) {
            if ($this->isBookValid($book, $exclusionList)) {
                $user->last_book_index = $startIndex + $index; // Update last book index
                $user->save();
                $validBooks[] = $this->formatBookDetails($book);
                if (count($validBooks) >= $numBooks) {
                    break; // Exit loop once the desired number of books is reached
                }
            }
        }
    
        if (count($validBooks) < $numBooks) {
            // If fewer valid books found, increment startIndex and try again
            $startIndex += 40;
            $additionalBooks = $this->getRecommendations($genres, $exclusionList, $userId, $startIndex, $numBooks - count($validBooks));
            $validBooks = array_merge($validBooks, $additionalBooks);
        }
    
        return $validBooks;
    }      
    
    /**
     * Merge books from relevance and newest queries, prioritizing books that appear in both lists.
     */
    private function mergeBooks(?array $booksRelevance, ?array $booksNewest)
    {
        $mergedBooks = [];
    
        if ($booksRelevance === null) {
            $booksRelevance = [];
        }
    
        if ($booksNewest === null) {
            $booksNewest = [];
        }
    
        $booksNewestIds = array_column($booksNewest, 'id');
    
        foreach ($booksRelevance as $book) {
            if (in_array($book['id'], $booksNewestIds)) {
                $mergedBooks[] = $book;
            }
        }
    
        foreach ($booksNewest as $book) {
            if (!in_array($book, $mergedBooks) && count($mergedBooks) < 40) {
                $mergedBooks[] = $book;
            }
        }
    
        return $mergedBooks;
    }
    

    public function saveToDatabase($bookDetails)
    {
        try {
            Book::create($bookDetails);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
