#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "../include/product.h"
#include "../include/database.h"

#define MAX_INPUT 1024

void clear_input_buffer(void) {
    int c;
    while ((c = getchar()) != '\n' && c != EOF);
}

void get_string_input(char *buffer, size_t size, const char *prompt) {
    printf("%s: ", prompt);
    fgets(buffer, size, stdin);
    buffer[strcspn(buffer, "\n")] = 0;
}

double get_double_input(const char *prompt) {
    char buffer[64];
    double value;
    while (1) {
        printf("%s: ", prompt);
        if (fgets(buffer, sizeof(buffer), stdin) != NULL) {
            char *endptr;
            value = strtod(buffer, &endptr);
            if (endptr != buffer && (*endptr == '\n' || *endptr == '\0')) {
                return value;
            }
        }
        printf("Invalid input. Please enter a valid number.\n");
        clear_input_buffer();
    }
}

int get_int_input(const char *prompt) {
    char buffer[64];
    int value;
    while (1) {
        printf("%s: ", prompt);
        if (fgets(buffer, sizeof(buffer), stdin) != NULL) {
            char *endptr;
            value = strtol(buffer, &endptr, 10);
            if (endptr != buffer && (*endptr == '\n' || *endptr == '\0')) {
                return value;
            }
        }
        printf("Invalid input. Please enter a valid number.\n");
        clear_input_buffer();
    }
}

void add_new_product(void) {
    Product product = {0};
    
    get_string_input(product.name, MAX_STRING_LENGTH, "Enter product name");
    get_string_input(product.category, 50, "Enter category");
    get_string_input(product.description, MAX_TEXT_LENGTH, "Enter description");
    get_string_input(product.ingredients, MAX_TEXT_LENGTH, "Enter ingredients");
    get_string_input(product.allergens, MAX_TEXT_LENGTH, "Enter allergens (or 'none')");
    
    product.price = get_double_input("Enter price");
    product.prep_time = get_int_input("Enter preparation time (minutes)");
    
    get_string_input(product.image_url, MAX_STRING_LENGTH, "Enter image URL (or 'none')");
    get_string_input(product.availability, MAX_STRING_LENGTH, "Enter availability (comma-separated days)");
    get_string_input(product.special_instructions, MAX_TEXT_LENGTH, "Enter special instructions (or 'none')");
    
    if (add_product(&product)) {
        printf("\nProduct added successfully! Product ID: %d\n", product.id);
        print_product(&product);
    } else {
        printf("\nError adding product.\n");
    }
}

void search_product(void) {
    int id = get_int_input("Enter product ID to search");
    
    Product *product = get_product(id);
    if (product) {
        print_product(product);
        free(product);
    } else {
        printf("Product not found.\n");
    }
}

void update_existing_product(void) {
    int id = get_int_input("Enter product ID to update");
    
    Product *product = get_product(id);
    if (!product) {
        printf("Product not found.\n");
        return;
    }
    
    printf("\nCurrent product details:\n");
    print_product(product);
    printf("\nEnter new details (press Enter to keep current value):\n");
    
    char buffer[MAX_INPUT];
    
    get_string_input(buffer, MAX_STRING_LENGTH, "Enter new name (or press Enter to skip)");
    if (strlen(buffer) > 0) strncpy(product->name, buffer, MAX_STRING_LENGTH);
    
    get_string_input(buffer, 50, "Enter new category (or press Enter to skip)");
    if (strlen(buffer) > 0) strncpy(product->category, buffer, 50);
    
    // Update other fields similarly...
    
    if (update_product(product)) {
        printf("\nProduct updated successfully!\n");
        print_product(product);
    } else {
        printf("\nError updating product.\n");
    }
    
    free(product);
}

void delete_existing_product(void) {
    int id = get_int_input("Enter product ID to delete");
    
    if (delete_product(id)) {
        printf("Product deleted successfully!\n");
    } else {
        printf("Error deleting product.\n");
    }
}

void display_menu(void) {
    printf("\nBakery Product Management System\n");
    printf("1. Add new product\n");
    printf("2. Search product\n");
    printf("3. Update product\n");
    printf("4. Delete product\n");
    printf("5. Exit\n");
    printf("Enter your choice: ");
}

int main(void) {
    if (init_db() != SQLITE_OK) {
        fprintf(stderr, "Failed to initialize database\n");
        return 1;
    }
    
    printf("Welcome to Bakery Product Management System\n");
    
    int choice;
    do {
        display_menu();
        scanf("%d", &choice);
        clear_input_buffer();
        
        switch (choice) {
            case 1:
                add_new_product();
                break;
            case 2:
                search_product();
                break;
            case 3:
                update_existing_product();
                break;
            case 4:
                delete_existing_product();
                break;
            case 5:
                printf("Goodbye!\n");
                break;
            default:
                printf("Invalid choice. Please try again.\n");
        }
    } while (choice != 5);
    
    close_db();
    return 0;
}
