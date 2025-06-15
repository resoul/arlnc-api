package models

type ChannelMessage struct {
	ID        int    `json:"id"`
	ChannelID int    `json:"channel_id"`
	SenderID  int    `json:"sender_id"`
	Content   string `json:"content"`
	ImageURL  string `json:"image_url"`
	CreatedAt string `json:"created_at"`
}
