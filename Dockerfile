FROM golang:1.23.4

WORKDIR /app

COPY go.mod ./
COPY go.sum ./
RUN go mod download

COPY . .

RUN go build -o server .

EXPOSE 8080

CMD ["./server"]
