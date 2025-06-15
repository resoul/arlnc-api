package main

import (
	"airlance-server/db"
	"airlance-server/handlers"
	"github.com/gorilla/mux"
	"log"
	"net/http"
)

func main() {
	dbConn, err := db.Connect()
	if err != nil {
		log.Fatal("DB connection failed:", err)
	}

	userHandler := &handlers.UserHandler{DB: dbConn}
	msgHandler := &handlers.MessageHandler{DB: dbConn}
	chHandler := &handlers.ChannelHandler{DB: dbConn}
	grpHandler := &handlers.GroupHandler{DB: dbConn}
	channelMsgHandler := &handlers.ChannelMessageHandler{DB: dbConn}
	groupMsgHandler := &handlers.GroupMessageHandler{DB: dbConn}

	r := mux.NewRouter()

	// CRUD
	r.HandleFunc("/users", userHandler.CreateUser).Methods("POST")
	r.HandleFunc("/users/{id}", userHandler.GetUser).Methods("GET")
	r.HandleFunc("/users/{id}", userHandler.UpdateUser).Methods("PUT")
	r.HandleFunc("/users/{id}", userHandler.DeleteUser).Methods("DELETE")

	r.HandleFunc("/messages", msgHandler.CreatePrivateMessage).Methods("POST")
	r.HandleFunc("/messages/{id}", msgHandler.GetPrivateMessage).Methods("GET")
	r.HandleFunc("/messages/{id}", msgHandler.UpdatePrivateMessage).Methods("PUT")
	r.HandleFunc("/messages/{id}", msgHandler.DeletePrivateMessage).Methods("DELETE")

	// Channels
	r.HandleFunc("/channels", chHandler.CreateChannel).Methods("POST")
	r.HandleFunc("/channels/{id}", chHandler.GetChannel).Methods("GET")
	r.HandleFunc("/channels/{id}", chHandler.UpdateChannel).Methods("PUT")
	r.HandleFunc("/channels/{id}", chHandler.DeleteChannel).Methods("DELETE")

	// Groups
	r.HandleFunc("/groups", grpHandler.CreateGroup).Methods("POST")
	r.HandleFunc("/topics", grpHandler.CreateTopic).Methods("POST")

	// Channel Messages
	r.HandleFunc("/channel-messages", channelMsgHandler.CreateChannelMessage).Methods("POST")
	r.HandleFunc("/channel-messages/{id}", channelMsgHandler.GetChannelMessage).Methods("GET")
	r.HandleFunc("/channel-messages/{id}", channelMsgHandler.UpdateChannelMessage).Methods("PUT")
	r.HandleFunc("/channel-messages/{id}", channelMsgHandler.DeleteChannelMessage).Methods("DELETE")

	// Group Messages
	r.HandleFunc("/group-messages", groupMsgHandler.CreateGroupMessage).Methods("POST")
	r.HandleFunc("/group-messages/{id}", groupMsgHandler.GetGroupMessage).Methods("GET")
	r.HandleFunc("/group-messages/{id}", groupMsgHandler.UpdateGroupMessage).Methods("PUT")
	r.HandleFunc("/group-messages/{id}", groupMsgHandler.DeleteGroupMessage).Methods("DELETE")

	log.Println("Server running at :8080")
	log.Fatal(http.ListenAndServe(":8080", r))
}
