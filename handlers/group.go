package handlers

import (
	"airlance-server/models"
	"database/sql"
	"encoding/json"
	"net/http"
)

type GroupHandler struct {
	DB *sql.DB
}

func (h *GroupHandler) CreateGroup(w http.ResponseWriter, r *http.Request) {
	var g models.Group
	if err := json.NewDecoder(r.Body).Decode(&g); err != nil {
		http.Error(w, "Invalid", http.StatusBadRequest)
		return
	}
	query := `INSERT INTO groups (creator_id, title, description)
	          VALUES ($1, $2, $3) RETURNING id, created_at`
	err := h.DB.QueryRow(query, g.CreatorID, g.Title, g.Description).
		Scan(&g.ID, &g.CreatedAt)
	if err != nil {
		http.Error(w, "Insert failed", http.StatusInternalServerError)
		return
	}
	json.NewEncoder(w).Encode(g)
}

func (h *GroupHandler) CreateTopic(w http.ResponseWriter, r *http.Request) {
	var t models.Topic
	if err := json.NewDecoder(r.Body).Decode(&t); err != nil {
		http.Error(w, "Invalid", http.StatusBadRequest)
		return
	}
	query := `INSERT INTO topics (group_id, title)
	          VALUES ($1, $2) RETURNING id, created_at`
	err := h.DB.QueryRow(query, t.GroupID, t.Title).
		Scan(&t.ID, &t.CreatedAt)
	if err != nil {
		http.Error(w, "Insert failed", http.StatusInternalServerError)
		return
	}
	json.NewEncoder(w).Encode(t)
}
