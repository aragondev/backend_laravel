<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
     function  can_get_all_books() {
        $books = Book::factory(4)->create();

        $this->getJson(route('books.index'))->assertJsonFragment(
            ['title' => $books[0]->title]
        )->assertJsonFragment(
            ['title' => $books[1]->title]
        )->assertJsonFragment(
            ['title' => $books[2]->title]
        )->assertJsonFragment(
            ['title' => $books[3]->title]
        );

     }

     /** @test */
        function  can_get_a_single_book() {
            $book = Book::factory()->create();

            $this->getJson(route('books.show', $book))->assertJsonFragment(
                ['title' => $book->title]
            );
        }

        /** @test */
        function  can_create_a_book() {
            $book = Book::factory()->make();

            $this->postJson(route('books.store'), [])->assertJsonValidationErrorFor('title');
            $this->postJson(route('books.store'), $book->toArray());

            $this->assertDatabaseHas('books', $book->toArray());
        }

        /** @test */
        function  can_update_a_book() {
            $book = Book::factory()->create();
            $book->title = 'New Title Updated';

            $this->putJson(route('books.update', $book), [])->assertJsonValidationErrorFor('title');
            $this->putJson(route('books.update', $book), $book->toArray());

            $this->assertDatabaseHas('books', $book->toArray());
        }

        /** @test */
        function  can_delete_a_book() {
            $book = Book::factory()->create();

            $this->deleteJson(route('books.destroy', $book));

            $this->assertDatabaseMissing('books', $book->toArray());
        }
}
