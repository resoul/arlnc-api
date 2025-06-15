package handlers

import (
	"airlance-server/models"
	"database/sql"
	"encoding/json"
	"github.com/gorilla/mux"
	"net/http"
)

type ChannelHandler struct {
	DB *sql.DB
}

func (h *ChannelHandler) CreateChannel(w http.ResponseWriter, r *http.Request) {
	var ch models.Channel
	if err := json.NewDecoder(r.Body).Decode(&ch); err != nil {
		http.Error(w, "Invalid request", http.StatusBadRequest)
		return
	}

	query := `INSERT INTO channels (owner_id, title, description, avatar_url)
	          VALUES ($1, $2, $3, $4) RETURNING id, created_at`
	err := h.DB.QueryRow(query, ch.OwnerID, ch.Title, ch.Description, ch.AvatarURL).
		Scan(&ch.ID, &ch.CreatedAt)
	if err != nil {
		http.Error(w, "Insert failed", http.StatusInternalServerError)
		return
	}

	json.NewEncoder(w).Encode(ch)
}

func (h *ChannelHandler) GetChannel(w http.ResponseWriter, r *http.Request) {
	id := mux.Vars(r)["id"]
	var ch models.Channel
	err := h.DB.QueryRow(`SELECT id, owner_id, title, description, avatar_url, created_at
	                      FROM channels WHERE id = $1`, id).
		Scan(&ch.ID, &ch.OwnerID, &ch.Title, &ch.Description, &ch.AvatarURL, &ch.CreatedAt)
	if err != nil {
		http.Error(w, "Not found", http.StatusNotFound)
		return
	}
	json.NewEncoder(w).Encode(ch)
}

func (h *ChannelHandler) UpdateChannel(w http.ResponseWriter, r *http.Request) {
	id := mux.Vars(r)["id"]
	var ch models.Channel
	if err := json.NewDecoder(r.Body).Decode(&ch); err != nil {
		http.Error(w, "Invalid", http.StatusBadRequest)
		return
	}
	_, err := h.DB.Exec(`UPDATE channels SET title=$1, description=$2, avatar_url=$3 WHERE id=$4`,
		ch.Title, ch.Description, ch.AvatarURL, id)
	if err != nil {
		http.Error(w, "Update failed", http.StatusInternalServerError)
		return
	}
	w.WriteHeader(http.StatusNoContent)
}

func (h *ChannelHandler) DeleteChannel(w http.ResponseWriter, r *http.Request) {
	id := mux.Vars(r)["id"]
	_, err := h.DB.Exec(`DELETE FROM channels WHERE id = $1`, id)
	if err != nil {
		http.Error(w, "Delete failed", http.StatusInternalServerError)
		return
	}
	w.WriteHeader(http.StatusNoContent)
}
