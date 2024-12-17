#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include "../include/product.h"
#include "../include/database.h"

int add_product(Product *product) {
    char query[4096];
    time_t now = time(NULL);
    product->created_at = now;
    product->updated_at = now;
    
    snprintf(query, sizeof(query),
        "INSERT INTO products (name, category, description, ingredients, "
        "allergens, price, prep_time, image_url, availability, special_instructions, "
        "created_at, updated_at) VALUES ('%s', '%s', '%s', '%s', '%s', %.2f, %d, "
        "'%s', '%s', '%s', datetime('now'), datetime('now'));",
        product->name,
        product->category,
        product->description,
        product->ingredients,
        product->allergens,
        product->price,
        product->prep_time,
        product->image_url,
        product->availability,
        product->special_instructions
    );
    
    int rc = execute_query(query);
    if (rc == SQLITE_OK) {
        product->id = get_last_insert_id();
        return 1;
    }
    return 0;
}

Product* get_product(int id) {
    char query[256];
    snprintf(query, sizeof(query), "SELECT * FROM products WHERE id = %d;", id);
    
    sqlite3_stmt *stmt;
    int rc = sqlite3_prepare_v2(db, query, -1, &stmt, 0);
    
    if (rc != SQLITE_OK) {
        fprintf(stderr, "Failed to fetch data: %s\n", sqlite3_errmsg(db));
        return NULL;
    }
    
    Product *product = NULL;
    if (sqlite3_step(stmt) == SQLITE_ROW) {
        product = malloc(sizeof(Product));
        product->id = sqlite3_column_int(stmt, 0);
        strncpy(product->name, (const char*)sqlite3_column_text(stmt, 1), MAX_STRING_LENGTH);
        strncpy(product->category, (const char*)sqlite3_column_text(stmt, 2), 50);
        strncpy(product->description, (const char*)sqlite3_column_text(stmt, 3), MAX_TEXT_LENGTH);
        strncpy(product->ingredients, (const char*)sqlite3_column_text(stmt, 4), MAX_TEXT_LENGTH);
        strncpy(product->allergens, (const char*)sqlite3_column_text(stmt, 5), MAX_TEXT_LENGTH);
        product->price = sqlite3_column_double(stmt, 6);
        product->prep_time = sqlite3_column_int(stmt, 7);
        strncpy(product->image_url, (const char*)sqlite3_column_text(stmt, 8), MAX_STRING_LENGTH);
        strncpy(product->availability, (const char*)sqlite3_column_text(stmt, 9), MAX_STRING_LENGTH);
        strncpy(product->special_instructions, (const char*)sqlite3_column_text(stmt, 10), MAX_TEXT_LENGTH);
    }
    
    sqlite3_finalize(stmt);
    return product;
}

int update_product(Product *product) {
    char query[4096];
    product->updated_at = time(NULL);
    
    snprintf(query, sizeof(query),
        "UPDATE products SET name = '%s', category = '%s', description = '%s', "
        "ingredients = '%s', allergens = '%s', price = %.2f, prep_time = %d, "
        "image_url = '%s', availability = '%s', special_instructions = '%s', "
        "updated_at = datetime('now') WHERE id = %d;",
        product->name,
        product->category,
        product->description,
        product->ingredients,
        product->allergens,
        product->price,
        product->prep_time,
        product->image_url,
        product->availability,
        product->special_instructions,
        product->id
    );
    
    return execute_query(query) == SQLITE_OK;
}

int delete_product(int id) {
    char query[256];
    snprintf(query, sizeof(query), "DELETE FROM products WHERE id = %d;", id);
    return execute_query(query) == SQLITE_OK;
}

void print_product(Product *product) {
    printf("Product ID: %d\n", product->id);
    printf("Name: %s\n", product->name);
    printf("Category: %s\n", product->category);
    printf("Description: %s\n", product->description);
    printf("Ingredients: %s\n", product->ingredients);
    printf("Allergens: %s\n", product->allergens);
    printf("Price: %.2f\n", product->price);
    printf("Preparation Time: %d minutes\n", product->prep_time);
    printf("Image URL: %s\n", product->image_url);
    printf("Availability: %s\n", product->availability);
    printf("Special Instructions: %s\n", product->special_instructions);
}
