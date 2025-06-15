package handlers

import (
	"airlance-server/models"
	"database/sql"
	"encoding/json"
	"github.com/gorilla/mux"
	"net/http"
)

type GroupMessageHandler struct {
	DB *sql.DB
}

func (h *GroupMessageHandler) CreateGroupMessage(w http.ResponseWriter, r *http.Request) {
	var msg models.GroupMessage
	if err := json.NewDecoder(r.Body).Decode(&msg); err != nil {
		http.Error(w, "Invalid", http.StatusBadRequest)
		return
	}

	query := `INSERT INTO group_messages (group_id, topic_id, sender_id, content, image_url)
	          VALUES ($1, $2, $3, $4, $5) RETURNING id, created_at`
	err := h.DB.QueryRow(query, msg.GroupID, msg.TopicID, msg.SenderID, msg.Content, msg.ImageURL).
		Scan(&msg.ID, &msg.CreatedAt)
	if err != nil {
		http.Error(w, "Insert failed", http.StatusInternalServerError)
		return
	}
	json.NewEncoder(w).Encode(msg)
}

func (h *GroupMessageHandler) GetGroupMessage(w http.ResponseWriter, r *http.Request) {
	id := mux.Vars(r)["id"]
	var msg models.GroupMessage
	err := h.DB.QueryRow(`SELECT id, group_id, topic_id, sender_id, content, image_url, created_at
	                      FROM group_messages WHERE id = $1`, id).
		Scan(&msg.ID, &msg.GroupID, &msg.TopicID, &msg.SenderID, &msg.Content, &msg.ImageURL, &msg.CreatedAt)
	if err != nil {
		http.Error(w, "Not found", http.StatusNotFound)
		return
	}
	json.NewEncoder(w).Encode(msg)
}

func (h *GroupMessageHandler) UpdateGroupMessage(w http.ResponseWriter, r *http.Request) {
	id := mux.Vars(r)["id"]
	var msg models.GroupMessage
	if err := json.NewDecoder(r.Body).Decode(&msg); err != nil {
		http.Error(w, "Invalid input", http.StatusBadRequest)
		return
	}

	_, err := h.DB.Exec(`UPDATE group_messages SET content=$1, image_url=$2 WHERE id=$3`,
		msg.Content, msg.ImageURL, id)
	if err != nil {
		http.Error(w, "Update failed", http.StatusInternalServerError)
		return
	}
	w.WriteHeader(http.StatusNoContent)
}

func (h *GroupMessageHandler) DeleteGroupMessage(w http.ResponseWriter, r *http.Request) {
	id := mux.Vars(r)["id"]
	_, err := h.DB.Exec(`DELETE FROM group_messages WHERE id = $1`, id)
	if err != nil {
		http.Error(w, "Delete failed", http.StatusInternalServerError)
		return
	}
	w.WriteHeader(http.StatusNoContent)
}
