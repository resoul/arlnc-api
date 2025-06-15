package handlers

import (
	"airlance-server/models"
	"database/sql"
	"encoding/json"
	"github.com/gorilla/mux"
	"net/http"
)

type UserHandler struct {
	DB *sql.DB
}

func (h *UserHandler) CreateUser(w http.ResponseWriter, r *http.Request) {
	var user models.User
	if err := json.NewDecoder(r.Body).Decode(&user); err != nil {
		http.Error(w, "Invalid request", http.StatusBadRequest)
		return
	}

	query := `INSERT INTO users (username, display_name, avatar_url) VALUES ($1, $2, $3) RETURNING id, created_at`
	err := h.DB.QueryRow(query, user.Username, user.DisplayName, user.AvatarURL).Scan(&user.ID, &user.CreatedAt)
	if err != nil {
		http.Error(w, "Failed to insert user", http.StatusInternalServerError)
		return
	}

	json.NewEncoder(w).Encode(user)
}

func (h *UserHandler) GetUser(w http.ResponseWriter, r *http.Request) {
	id := mux.Vars(r)["id"]

	var user models.User
	err := h.DB.QueryRow("SELECT id, username, display_name, avatar_url, created_at FROM users WHERE id = $1", id).
		Scan(&user.ID, &user.Username, &user.DisplayName, &user.AvatarURL, &user.CreatedAt)
	if err != nil {
		http.Error(w, "User not found", http.StatusNotFound)
		return
	}

	json.NewEncoder(w).Encode(user)
}

func (h *UserHandler) UpdateUser(w http.ResponseWriter, r *http.Request) {
	id := mux.Vars(r)["id"]
	var user models.User

	if err := json.NewDecoder(r.Body).Decode(&user); err != nil {
		http.Error(w, "Invalid data", http.StatusBadRequest)
		return
	}

	query := `UPDATE users SET username=$1, display_name=$2, avatar_url=$3 WHERE id=$4`
	_, err := h.DB.Exec(query, user.Username, user.DisplayName, user.AvatarURL, id)
	if err != nil {
		http.Error(w, "Update failed", http.StatusInternalServerError)
		return
	}

	w.WriteHeader(http.StatusNoContent)
}

func (h *UserHandler) DeleteUser(w http.ResponseWriter, r *http.Request) {
	id := mux.Vars(r)["id"]
	_, err := h.DB.Exec("DELETE FROM users WHERE id=$1", id)
	if err != nil {
		http.Error(w, "Delete failed", http.StatusInternalServerError)
		return
	}
	w.WriteHeader(http.StatusNoContent)
}
