#ifndef PRODUCT_H
#define PRODUCT_H

#include <time.h>

#define MAX_STRING_LENGTH 255
#define MAX_TEXT_LENGTH 1000

typedef struct {
    int id;
    char name[MAX_STRING_LENGTH];
    char category[50];
    char description[MAX_TEXT_LENGTH];
    char ingredients[MAX_TEXT_LENGTH];
    char allergens[MAX_TEXT_LENGTH];
    double price;
    int prep_time;
    char image_url[MAX_STRING_LENGTH];
    char availability[MAX_STRING_LENGTH];
    char special_instructions[MAX_TEXT_LENGTH];
    time_t created_at;
    time_t updated_at;
} Product;

// Function declarations
int init_database(void);
int add_product(Product *product);
Product* get_product(int id);
int update_product(Product *product);
int delete_product(int id);
void print_product(Product *product);

#endif // PRODUCT_H
