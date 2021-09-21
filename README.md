## First Page Digital - Test #2

This is a running PHP application and can be tested accordingly (without any security).

### Main Files

* `routes/api.php` - This is where I set the route for the API. The API route gets a prefix of `/api`
* `app/Http/Controllers/ProductController.php` - main file to handle API requests
* `app/Http/Requests/ProductPostRequest.php` - handles data validation when you perform `POST` request to create a product

**Models**

* `app/Models/Product.php` - is the main model for product and is connected to `products` table
* `app/Models/Stock.php` - is the model representation for `stocks` table

The relationship was also established in these 2 files.

**Supplementary Files**

* `database/migrations/2021_09_21_134548_create_products_table.php` - product schema
* `database/migrations/2021_09_21_134605_create_stocks_table.php` - stocks schema

### How it works

The following code in `routes/api.php` registers 7 routes but I have only implemented 3.

```php
Route::resource('products', \App\Http\Controllers\ProductController::class);
```
These are the routes that it generated.

```bash
|        | GET|HEAD  | api/products                | products.index   | App\Http\Controllers\ProductController@index               |            |
|        | POST      | api/products                | products.store   | App\Http\Controllers\ProductController@store               |            |
|        | GET|HEAD  | api/products/create         | products.create  | App\Http\Controllers\ProductController@create              |            |
|        | GET|HEAD  | api/products/{product}      | products.show    | App\Http\Controllers\ProductController@show                |            |
|        | PUT|PATCH | api/products/{product}      | products.update  | App\Http\Controllers\ProductController@update              |            |
|        | DELETE    | api/products/{product}      | products.destroy | App\Http\Controllers\ProductController@destroy             |            |
|        | GET|HEAD  | api/products/{product}/edit | products.edit    | App\Http\Controllers\ProductController@edit                |            |

```
These are the routes that I have implemented.
```bash
|        | GET|HEAD  | api/products                | products.index   | App\Http\Controllers\ProductController@index               |            |
|        | POST      | api/products                | products.store   | App\Http\Controllers\ProductController@store               |            |
|        | GET|HEAD  | api/products/{product}      | products.show    | App\Http\Controllers\ProductController@show                |            |
```
The special route in the implementation is `POST api/products` route because it uses validation performed by the file `app/Http/Requests/ProductPostRequest.php`. You can see the follwing:
```php
    public function rules()
    {
        return [
            'name' => 'required|unique:products|min:3', // minimum of 3 characters
            'price' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Product name is required',
            'name.unique' => 'Product name already exists.',
            'name.min' => 'Product name must be at least 3 characters long',
            'price.required' => 'Product price is required',
            'price.numeric' => 'Price must be a numeric value',
        ];
    }
```
The `messages` function is not really necessary, it is just there to customize the error messages.

### In Action

When I perform this request:
```http request
POST http://127.0.0.1:8000/api/products
Content-Type: application/json
Accept: application/json

{
    "name": "TV",
    "price": "Price",
    "description": "The quick brown fox jumped over the lazy dog."
}
```
It returns the following:
```bash
{
  "message": "The given data was invalid.",
  "errors": {
    "name": [
      "Product name must be at least 3 characters long"
    ],
    "price": [
      "Price must be a numeric value"
    ]
  }
}
```
Now, if I provide a valid input:
```http request
{
    "name": "Television",
    "price": 19.0,
    "description": "The quick brown fox jumped over the lazy dog."
}
```
It will return the following response:
```http request
{
  "name": "Television",
  "price": 19,
  "description": "The quick brown fox jumped over the lazy dog.",
  "updated_at": "2021-09-21T15:38:42.000000Z",
  "created_at": "2021-09-21T15:38:42.000000Z",
  "id": 1
}
```
And when I perform the request again, this is the result:
```http request
{
  "message": "The given data was invalid.",
  "errors": {
    "name": [
      "Product name already exists."
    ]
  }
}
```
