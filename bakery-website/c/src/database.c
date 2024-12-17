#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "../include/database.h"

sqlite3 *db = NULL;

int init_db(void) {
    int rc = sqlite3_open(DB_FILE, &db);
    
    if (rc) {
        fprintf(stderr, "Can't open database: %s\n", sqlite3_errmsg(db));
        return rc;
    }
    
    const char *sql = 
        "CREATE TABLE IF NOT EXISTS products ("
        "id INTEGER PRIMARY KEY AUTOINCREMENT,"
        "name TEXT NOT NULL,"
        "category TEXT NOT NULL,"
        "description TEXT,"
        "ingredients TEXT,"
        "allergens TEXT,"
        "price REAL NOT NULL,"
        "prep_time INTEGER,"
        "image_url TEXT,"
        "availability TEXT,"
        "special_instructions TEXT,"
        "created_at DATETIME DEFAULT CURRENT_TIMESTAMP,"
        "updated_at DATETIME DEFAULT CURRENT_TIMESTAMP"
        ");";
    
    char *err_msg = NULL;
    rc = sqlite3_exec(db, sql, 0, 0, &err_msg);
    
    if (rc != SQLITE_OK) {
        fprintf(stderr, "SQL error: %s\n", err_msg);
        sqlite3_free(err_msg);
        return rc;
    }
    
    return SQLITE_OK;
}

void close_db(void) {
    if (db) {
        sqlite3_close(db);
        db = NULL;
    }
}

int execute_query(const char *query) {
    char *err_msg = NULL;
    int rc = sqlite3_exec(db, query, 0, 0, &err_msg);
    
    if (rc != SQLITE_OK) {
        fprintf(stderr, "SQL error: %s\n", err_msg);
        sqlite3_free(err_msg);
        return rc;
    }
    
    return SQLITE_OK;
}

int get_last_insert_id(void) {
    return sqlite3_last_insert_rowid(db);
}
