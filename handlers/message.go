package handlers

import (
	"airlance-server/models"
	"encoding/json"
	"net/http"

	"database/sql"
	"github.com/gorilla/mux"
)

type MessageHandler struct {
	DB *sql.DB
}

// Create private message
func (h *MessageHandler) CreatePrivateMessage(w http.ResponseWriter, r *http.Request) {
	var msg models.PrivateMessage
	if err := json.NewDecoder(r.Body).Decode(&msg); err != nil {
		http.Error(w, "Invalid request", http.StatusBadRequest)
		return
	}

	query := `INSERT INTO private_messages (sender_id, receiver_id, content, image_url)
	          VALUES ($1, $2, $3, $4) RETURNING id, created_at`
	err := h.DB.QueryRow(query, msg.SenderID, msg.ReceiverID, msg.Content, msg.ImageURL).
		Scan(&msg.ID, &msg.CreatedAt)
	if err != nil {
		http.Error(w, "Failed to send message", http.StatusInternalServerError)
		return
	}

	json.NewEncoder(w).Encode(msg)
}

// Get message by ID
func (h *MessageHandler) GetPrivateMessage(w http.ResponseWriter, r *http.Request) {
	id := mux.Vars(r)["id"]

	var msg models.PrivateMessage
	err := h.DB.QueryRow(`SELECT id, sender_id, receiver_id, content, image_url, created_at
	                      FROM private_messages WHERE id = $1`, id).
		Scan(&msg.ID, &msg.SenderID, &msg.ReceiverID, &msg.Content, &msg.ImageURL, &msg.CreatedAt)
	if err != nil {
		http.Error(w, "Message not found", http.StatusNotFound)
		return
	}

	json.NewEncoder(w).Encode(msg)
}

// Update content/image of message
func (h *MessageHandler) UpdatePrivateMessage(w http.ResponseWriter, r *http.Request) {
	id := mux.Vars(r)["id"]
	var msg models.PrivateMessage

	if err := json.NewDecoder(r.Body).Decode(&msg); err != nil {
		http.Error(w, "Invalid data", http.StatusBadRequest)
		return
	}

	query := `UPDATE private_messages SET content = $1, image_url = $2 WHERE id = $3`
	_, err := h.DB.Exec(query, msg.Content, msg.ImageURL, id)
	if err != nil {
		http.Error(w, "Update failed", http.StatusInternalServerError)
		return
	}

	w.WriteHeader(http.StatusNoContent)
}

// Delete message
func (h *MessageHandler) DeletePrivateMessage(w http.ResponseWriter, r *http.Request) {
	id := mux.Vars(r)["id"]

	_, err := h.DB.Exec("DELETE FROM private_messages WHERE id = $1", id)
	if err != nil {
		http.Error(w, "Delete failed", http.StatusInternalServerError)
		return
	}

	w.WriteHeader(http.StatusNoContent)
}
