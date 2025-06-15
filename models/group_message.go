package models

type GroupMessage struct {
	ID        int    `json:"id"`
	GroupID   int    `json:"group_id"`
	TopicID   *int   `json:"topic_id,omitempty"` // optional
	SenderID  int    `json:"sender_id"`
	Content   string `json:"content"`
	ImageURL  string `json:"image_url"`
	CreatedAt string `json:"created_at"`
}
