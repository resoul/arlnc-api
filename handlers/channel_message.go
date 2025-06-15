package handlers

import (
	"airlance-server/models"
	"database/sql"
	"encoding/json"
	"github.com/gorilla/mux"
	"net/http"
)

type ChannelMessageHandler struct {
	DB *sql.DB
}

func (h *ChannelMessageHandler) CreateChannelMessage(w http.ResponseWriter, r *http.Request) {
	var msg models.ChannelMessage
	if err := json.NewDecoder(r.Body).Decode(&msg); err != nil {
		http.Error(w, "Invalid input", http.StatusBadRequest)
		return
	}

	// Опционально: проверка — sender_id == owner_id для этого channel_id

	query := `INSERT INTO channel_messages (channel_id, sender_id, content, image_url)
	          VALUES ($1, $2, $3, $4) RETURNING id, created_at`
	err := h.DB.QueryRow(query, msg.ChannelID, msg.SenderID, msg.Content, msg.ImageURL).
		Scan(&msg.ID, &msg.CreatedAt)
	if err != nil {
		http.Error(w, "Insert failed", http.StatusInternalServerError)
		return
	}

	json.NewEncoder(w).Encode(msg)
}

func (h *ChannelMessageHandler) GetChannelMessage(w http.ResponseWriter, r *http.Request) {
	id := mux.Vars(r)["id"]
	var msg models.ChannelMessage
	err := h.DB.QueryRow(`SELECT id, channel_id, sender_id, content, image_url, created_at
	                      FROM channel_messages WHERE id = $1`, id).
		Scan(&msg.ID, &msg.ChannelID, &msg.SenderID, &msg.Content, &msg.ImageURL, &msg.CreatedAt)
	if err != nil {
		http.Error(w, "Not found", http.StatusNotFound)
		return
	}
	json.NewEncoder(w).Encode(msg)
}

func (h *ChannelMessageHandler) UpdateChannelMessage(w http.ResponseWriter, r *http.Request) {
	id := mux.Vars(r)["id"]
	var msg models.ChannelMessage
	if err := json.NewDecoder(r.Body).Decode(&msg); err != nil {
		http.Error(w, "Invalid input", http.StatusBadRequest)
		return
	}

	_, err := h.DB.Exec(`UPDATE channel_messages SET content=$1, image_url=$2 WHERE id=$3`,
		msg.Content, msg.ImageURL, id)
	if err != nil {
		http.Error(w, "Update failed", http.StatusInternalServerError)
		return
	}
	w.WriteHeader(http.StatusNoContent)
}

func (h *ChannelMessageHandler) DeleteChannelMessage(w http.ResponseWriter, r *http.Request) {
	id := mux.Vars(r)["id"]
	_, err := h.DB.Exec(`DELETE FROM channel_messages WHERE id = $1`, id)
	if err != nil {
		http.Error(w, "Delete failed", http.StatusInternalServerError)
		return
	}
	w.WriteHeader(http.StatusNoContent)
}
