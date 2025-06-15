package db

import (
	"database/sql"
	_ "github.com/lib/pq"
)

func Connect() (*sql.DB, error) {
	connStr := "host=postgres port=5432 user=postgres password=postgres dbname=appdb sslmode=disable"
	return sql.Open("postgres", connStr)
}
