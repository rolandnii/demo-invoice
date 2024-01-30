## Invoicing System API Documentation

### Table of Contents
- [Authentication](#authentication)
	- [Create Account](#create-account)
		- [Customer](#customer)
		- [Admin](#admin)
	- [Get API Token](#get-api-token)
	- [Using API Token in Authorization Header](#using-api-token-in-authorization-header)
	- [Using API Token in Request Query](#using-api-token-in-request-query)
- [Endpoints](#endpoints)
	- [Item](#item)
		- [Create Item](#create-item)
		- [Get Item](#get-item)
		- [Update Item](#update-item)
		- [Delete Item](#delete-item)
		- [List Item](#list-item)
	- [Invoice](#invoice)
		- [Create Invoice](#create-invoice)
		- [Get Invoice](#get-invoice)
		- [Delete Invoice](#delete-invoice)
		- [List Invoice](#list-invoice)

# Authentication

## Create Account

### Customer
To create a new customer account, send a post request to `api/register`  with name, email address and password.
```http
POST /api/register
```
Request
```json
{
  "name": "Roland Nii",
  "email": "test@me.com",
  "password": "password"
}
```
Sample Response
```json
{
	"ok": true,
	"msg": "Customer account created successfully",
}
```

### Admin
To create a new admin account, send a post request to `api/admin/register`  with name, email address and password.
```http
POST /api/admin/register
```
Request
```json
{
  "name": "Edmund Alabi",
  "email": "edmund@shaq.com",
  "password": "password"
}
```
Sample Response
```json
{
	"ok": true,
	"msg": "Admin account created successfully",
}
```


## Get API Token
To access the API, you will need to obtain an access token. You can do this by sending a POST request to the `api/login` endpoint with your email address and password.
```http
POST /api/login
```
Request
```json
{
  "email": "test@me.com",
  "password": "password"
}
```
Sample Response
```json
{
	"ok": true,
	"msg": "Login successful",
	"data": {
		"name": "Roland Dodoo",
		"email": "test@me.com",
		"usertype": "customer",
		"user_id": "01hf0e37d0db737qzxegyx6312",
		"token": "6|SGCUo1vR9BUpJW1jL0a6YjjpoDCpEBLMhWGDAXBn81075189"
	}
}
```
## Using API Token in Authorization Header

Include your API token in the Authorization header using the Bearer toke format.
```http
GET /api/endpoint
Authorization: Bearer YOUR_API_Token
```
## Using API Token in Request Query
Include  your API token in a request query.
```http
GET /api/endpoint?auth_key=YOUR_API_Token
```
# Endpoints

## Item

### Create Item
Create a new item. Only an admin can access this api
```http
POST /api/item
Content-Type: application/json
```
Request:
```json
{
	"description": "sandals men",
	"unit_price": "2023-10-21",
	"total_quantity": "2023-11-21",
}
```
Sample Response
```json
{
	"ok": true,
	"msg": "New item created successfully"
}
```
### Get Item
Get details of a specific item
```http
GET /api/item/{item_id}
```
Example
```http
GET /api/item/1
```
Sample Response
```json
{
	"ok": true,
	"msg": "Item details fetched successfully",
	"data": {
		"description": "Socks",
		"unit_price": "12.50",
		"total_quantity": 25
	}
}

```
### Update Item
Update an existing item.  Only an admin can access this api
```http
POST /api/item/update/{item_id}
```
Example 
```http
POST /api/item/update/1
```
Request:
```json
{
	
	"description": "sandals men",
	"unit_price": "2023-10-21",
	"total_quantity": "2023-11-21",
}
```
Sample Response
```json
{
	"ok": true,
	"msg": "Item updated successfully"
}
```
### Delete Item
Delete a specific item.  Only an admin can access this api
Get details of a specific item
```http
DELETE /api/item/{item_id}
```
Example
```http
DELETE /api/item/1
```
Sample Response
```json
{
    "ok": true,
    "msg": "item deleted successfully",
}
```

### List Item
Retrieve a list of all items.
```http
GET /api/item/
```
Sample Response
```json
{
	"ok": true,
	"msg": "Items fetched successfully",
	"data": [
		{
			"description": "Socks",
			"unit_price": "12.50",
			"total_quantity": 25
		},
		{
			"description": "Shirt",
			"unit_price": "20.00",
			"total_quantity": 11
		},
		{
			"description": "Sandals",
			"unit_price": "35.00",
			"total_quantity": 10
		},
		{
			"description": "sandals men",
			"unit_price": "20.00",
			"total_quantity": 10
		}
	]
}
```












## Invoice

### Create Invoice
Create a new invoice.
```http
POST /api/invoice
Content-Type: application/json
```
Request:
```json
{
	"items": [
		
		{
			"item_code": 2,
			"quantity": 1
		}
	],
	"customer_id": "01hf0e37d0db737qzxegyx6312",
	"issue_date": "2023-10-21",
	"due_date": "2023-11-21"
}
```
Sample Response
```json
{
	"ok": true,
	"msg": "Creating an invoice successful"
}
```
### Get Invoice
Get details of a specific invoice
```http
GET /api/invoice/{invoice_id}
```
Example
```http
GET /api/invoice/INV-2023-11-0000
```
Sample Response
```json
{
	"ok": true,
	"msg": "Invoice details fetched successfully",
	"data": {
		"invoice_id": "INV-2023-11-0000",
		"customer_id": "01hf0e37d0db737qzxegyx6312",
		"customer_name": "Roland Dodoo",
		"customer_email": "test@me.com",
		"issue_date": "2023-10-21",
		"due_date": "2023-11-21",
		"total_amount": "20.00",
		"items": [
			{
				"description": "Shirt",
				"unit_price": "20.00",
				"subtotal": "20.00",
				"quantity": 1
			}
		]
	}
}
```
### Delete Invoice
Delete a specific invoice.
Get details of a specific invoice
```http
DELETE /api/invoice/{invoice_id}
```
Example
```http
DELETE /api/invoice/INV-2023-11-0000
```
Sample Response
```json
{
    "ok": true,
    "msg": "Invoice deleted successfully",
}
```

### List Invoice
Retrieve a list of all invoices.
```http
GET /api/invoice/
```
Sample Response
```json
{
	"ok": true,
	"msg": "All invoices fetched successfully",
	"data": [
		{
			"invoice_id": "INV-2023-11-0000",
			"customer_id": "01hf0e37d0db737qzxegyx6312",
			"customer_name": "Roland Dodoo",
			"customer_email": "test@me.com",
			"issue_date": "2023-10-21",
			"due_date": "2023-11-21",
			"total_amount": "20.00",
			"items": [
				{
					"description": "Shirt",
					"unit_price": "20.00",
					"subtotal": "20.00",
					"quantity": 1
				}
			]
		},
		{
			"invoice_id": "INV-2023-11-0001",
			"customer_id": "01hf0e37d0db737qzxegyx6312",
			"customer_name": "Roland Dodoo",
			"customer_email": "test@me.com",
			"issue_date": "2023-10-21",
			"due_date": "2023-11-21",
			"total_amount": "20.00",
			"items": [
				{
					"description": "Shirt",
					"unit_price": "20.00",
					"subtotal": "20.00",
					"quantity": 1
				}
			]
		}
	]
}
```
