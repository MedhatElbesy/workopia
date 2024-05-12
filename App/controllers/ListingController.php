<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Session;
use Framework\Validation;
use Framework\Authorization;
use Framework\Middleware\Authorize;

class ListingController {

    protected $db;
    public function __construct(){
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * Show all listings
     *
     * @return void
     */
    public function index(){
        $listings = $this->db->query('SELECT * FROM listings ORDER BY created_at DESC  ')->fetchAll();
        loadView('listings/index',['listings' => $listings]);
    }

    /**
     * Show create listing form
     *
     * @return void
     */
    public function create(){
        loadView('listings/create');
    }
    /**
     * Show a single listing
     * @param array  $params
     * @return void
     */
    public function show($params){
        $id =  $params['id'] ?? '';

        $params = [
            'id' => $id
        ];
        $listing = $this->db->query('select * from listings where id = :id',$params)->fetch();
        if(!$listing){
            ErrorController::notFound('Listing not found');
            return;
        }
        loadView('listings/show',['listing' => $listing]);
    }

    /**
     * Store data in database
     *
     * @return void
     */
    public function store(){
        $allowedFields = [
            'title','description','salary','tags',
            'company','address','city','state','phone',
            'email','requirements','benefits'
        ];
        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));

        $newListingData['user_id'] = Session::get('user')['id'];

        $newListingData = array_map('sanitize', $newListingData);
        $requiredFields = ['title','description','email','city','salary'];
        
        $errors = [];
        foreach($requiredFields as $field){
            if(empty($newListingData[$field]) || !Validation::string($newListingData[$field])){
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }
        if(!empty($errors)){
            loadView('listings/create',[
                'errors' => $errors,
                'listing' => $newListingData
            ]);
        }else{
            $fields = [];
            foreach($newListingData as $field => $value){
                $fields[] = $field;
            }
            $fields = implode(',',$fields);

            $values = [];
            foreach($newListingData as $field => $value){
                if($value === ''){
                    $newListingData[$field] = null;
                }
                $values [] = ':' . $field;
            }
            $values = implode(',',$values);

            $query = "insert into listings ({$fields}) values ({$values})";

            $this->db->query($query,$newListingData);

            Session::setFlashMessage('success_message','Listing Created successfully');

            redirect('/listings');
        }
    }

    /**
     * Show a listing edit form 
     * @param array  $params
     * @return void
     */
    public function edit($params){
        
        $id =  $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('select * from listings where id = :id',$params)->fetch();
        
        if(!$listing){
            ErrorController::notFound('Listing not found');
            return;
        }

        if(!Authorization::isOwner($listing->user_id)){
            Session::setFlashMessage('error_message','You are not authorized to delete this listings');
            return redirect('/listings/' . $listing->id);
        }

        loadView('listings/edit',['listing' => $listing]);
    }
    /**
     * Update a listings
     * 
     * @param string $params
     * @return void
     */
    public function update($params){
        $id =  $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('select * from listings where id = :id',$params)->fetch();
        
        if(!$listing){
            ErrorController::notFound('Listing not found');
            return;
        }

        if(!Authorization::isOwner($listing->user_id)){
            Session::setFlashMessage('error_message','You are not authorized to delete this listings');
            return redirect('/listings/' . $listing->id);
        }

        $allowedFields = [
            'title','description','salary','tags',
            'company','address','city','state','phone',
            'email','requirements','benefits'
        ];

        $updateValues = [];

        $updateValues = array_intersect_key($_POST, array_flip($allowedFields));

        $updateValues =  array_map('sanitize',$updateValues);

        $requiedFields = ['title','description','salary','email','city','state'];

        $errors = [];

        foreach($requiedFields as $field){
            if(empty($updateValues[$field]) || !Validation::string($updateValues[$field]) ){
                $errors[$field] = ucfirst($field) . 'is required';
            }
        }
        if(!empty($errors)){
            loadView('listings/edit',[
                'listing' => $listing,
                'errors' => $errors
            ]);
            exit;
        }else{
            $updateFields = [];
            foreach(array_keys($updateValues) as $field){
                $updateFields[] = "{$field} = :{$field}";
            }

            $updateFields = implode(',',$updateFields);

            $updateQuery = "UPDATE listings SET $updateFields WHERE id = :id";
            $updateValues['id'] = $id;
            $this->db->query($updateQuery, $updateValues);

            $_SESSION['success_message'] = "Listing Updated";
            Session::setFlashMessage('success_message','Listing Updated successfully');

            redirect('/listings/'.$id);
        }
        
    }


    /**
     * Delete a listing
     * @param array  $params
     * @return void
     */
    public function destroy($params){

        $id = $params['id'];

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('select * from listings where id = :id',$params)->fetch();
        
        if(!$listing){
            ErrorController::notFound('Listing not found');
            return;
        }

        if(!Authorization::isOwner($listing->user_id)){
            Session::setFlashMessage('error_message','You are not authorized to delete this listings');
            return redirect('/listings/' . $listing->id);
        }

        $this->db->query('delete from listings where id = :id',$params);
        
        Session::setFlashMessage('success_message','Listing deleted successfully');

        redirect('/listings');
    }

    /**
     * Search listings by keywords/location
     * 
     * @return void
     */
    public function search()
    {
        $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
        $location = isset($_GET['location']) ? trim($_GET['location']) : '';

        $query = "SELECT * FROM listings WHERE (title LIKE :keywords OR description LIKE :keywords OR tags LIKE :keywords OR company LIKE :keywords) AND (city LIKE :location OR state LIKE :location)";

        $params = [
        'keywords' => "%{$keywords}%",
        'location' => "%{$location}%"
        ];

        $listings = $this->db->query($query, $params)->fetchAll();

        loadView('/listings/index', [
        'listings' => $listings,
        'keywords' => $keywords,
        'location' => $location
        ]);
    }
}