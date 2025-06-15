package models

type PrivateMessage struct {
	ID         int    `json:"id"`
	SenderID   int    `json:"sender_id"`
	ReceiverID int    `json:"receiver_id"`
	Content    string `json:"content"`
	ImageURL   string `json:"image_url"`
	CreatedAt  string `json:"created_at"`
}
