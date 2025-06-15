package models

type Group struct {
	ID          int    `json:"id"`
	CreatorID   int    `json:"creator_id"`
	Title       string `json:"title"`
	Description string `json:"description"`
	CreatedAt   string `json:"created_at"`
}

type Topic struct {
	ID        int    `json:"id"`
	GroupID   int    `json:"group_id"`
	Title     string `json:"title"`
	CreatedAt string `json:"created_at"`
}
