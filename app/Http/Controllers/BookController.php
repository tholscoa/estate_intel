<?php

namespace App\Http\Controllers;

use App\Http\Services\APICallsService;
use App\Models\Author;
use App\Models\Book;
use App\Http\Services\CrudService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function fetchExternalBooks(Request $request){
        $input = $request->all();
        $list = APICallsService::makeAPICall("https://www.anapioficeandfire.com/api/books?name=". $input['name']);
        if(!$list[0]){
            return response(['status_code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'status' => "fail", 'data' => []], 422);
        }
        $params = [];

        if(count($list[1]) > 0){
            $i=0;
            foreach($list[1] as $list){
                $params[$i]["name"] = $list["name"];
                $params[$i]["isbn"]= $list["isbn"];
                $params[$i]["authors"]= $list["authors"];
                $params[$i]["number_of_pages"]= $list["numberOfPages"];
                $params[$i]["publisher"]= $list["publisher"];
                $params[$i]["country"]= $list["country"];
                //change date format
                $date = strtotime($list["released"]);
                $newDateFormat = date('Y-m-d',$date);

                $params[$i]["release_date"]= $newDateFormat;
                $i++;
            }
        }
        return response(['status_code' => Response::HTTP_OK, 'status' => "success", 'data' => $params], 200);

    }
    public function createBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'isbn' => 'required|string',
            'authors' => 'required|array',
            'country' => 'required|string',
            'number_of_pages' => 'required|integer',
            'publisher' => 'required|string',
            'release_date' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response(['status_code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'status' => "fail", 'data' => 'Validation errors. ' .  $validator->errors()], 422);
        }

        $input = $request->all();
        $name = $input['name'];
        $isbn = $input['isbn'];
        $authors = $input['authors'];
        $country = $input['country'];
        $number_of_pages = $input['number_of_pages'];
        $publisher = $input['publisher'];
        $release_date = $input['release_date'];
        
        $params = [
            "name"=>$name,
            "isbn"=>$isbn,
            "country"=>$country,
            "number_of_pages"=>$number_of_pages,
            "publisher"=>$publisher,
            "release_date"=>$release_date,
        ];
        // return $params;
        //create book record
        $create_book = CrudService::create(Book::class, $params);
        if(!$create_book[0]){
            return response()->json(['status_code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'status' => "fail", 'data' => $create_book[1]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        //save book authors
        foreach($authors as $key => $author){
            $data = [
                'book_id' => $create_book[1]['id'],
                'author' => $author
            ];
            $author = CrudService::create(Author::class, $data);
            if(!$author[0]){
                return response()->json(['status_code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'status' => "fail", 'message' => $author[1], 'data' => $author[1]], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return response()->json(['status_code' => Response::HTTP_CREATED, 'status' => "success", 'data' => ['book'=>$create_book[1]]], Response::HTTP_CREATED);

    }
 
    public function listBooks(){
        $list = CrudService::list(Book::class);
        if(!$list[0]){
            return response(['status_code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'status' => "fail", 'data' => []], 422);
        }
        return response(['status_code' => Response::HTTP_OK, 'status' => "success", 'data' => $list[1]], 200);

    }

    public function updateBooks(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'isbn' => 'string',
            'authors' => 'array',
            'country' => 'string',
            'number_of_pages' => 'integer',
            'publisher' => 'string',
            'release_date' => 'string',
        ]);

        $params = [];

        $input = $request->all();
        isset($input['name']) ? ($params['name'] = $input['name']) : false;
        isset($input['isbn']) ? ($params['isbn'] = $input['isbn']) : false;
        isset($input['country']) ? ($params['country'] = $input['country']) : false;
        isset($input['number_of_pages']) ? ($params['number_of_pages'] = $input['number_of_pages']) : false;
        isset($input['publisher']) ? ($params['publisher'] = $input['publisher']) : false;
        isset($input['release_date']) ? ($params['release_date'] = $input['release_date']) : false;
        
        $authors = isset($input['authors']) ?  $input['authors'] : false;

        //update book record
        $update_book = CrudService::update(Book::class, $params, $id);
        if(!$update_book[0]){
            return response()->json(['status_code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'status' => "fail", 'data' => $update_book[1]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        //if author is passed update book authors
        if($authors){
            foreach($authors as $key => $author){
                $data = [
                    'book_id' => $update_book[1]['id'],
                    'author' => $author
                ];
                $author = CrudService::create(Author::class, $data);
                if(!$author[0]){
                    return response()->json(['status_code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'status' => "fail", 'message' => $author[1], 'data' => $author[1]], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
        }

        return response()->json(['status_code' => Response::HTTP_OK, 'status' => "success", "message"=>"The book ". $update_book[1]['name'] ." was updated successfully", 'data' => $update_book[1]], Response::HTTP_OK);

    }

    public function deleteBooks($id)
    {
        $delete = CrudService::delete(Book::class, $id);
        if(!$delete[0]){
            return response()->json(['status_code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'status' => "fail", 'data' => $delete[1]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['status_code' => Response::HTTP_OK, 'status' => "success", "message"=>"The book '$delete[1]' was deleted successfully", 'data' => []], Response::HTTP_OK);

    }

    public function showBook($id)
    {
        $show = CrudService::show(Book::class, $id);
        if(!$show[0]){
            return response()->json(['status_code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'status' => "fail", 'data' => $show[1]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['status_code' => Response::HTTP_OK, 'status' => "success", 'data' => $show[1]], Response::HTTP_OK);

    }
}
