
-- Users
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username TEXT UNIQUE NOT NULL,
    display_name TEXT,
    avatar_url TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Messages (1-1 user messages)
CREATE TABLE messages (
    id SERIAL PRIMARY KEY,
    sender_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    receiver_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    content TEXT,
    media_url TEXT,
    deleted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Channels
CREATE TABLE channels (
    id SERIAL PRIMARY KEY,
    owner_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    name TEXT NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Channel Messages
CREATE TABLE channel_messages (
    id SERIAL PRIMARY KEY,
    channel_id INTEGER REFERENCES channels(id) ON DELETE CASCADE,
    sender_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    content TEXT,
    media_url TEXT,
    deleted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Channel Subscriptions
CREATE TABLE channel_subscriptions (
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    channel_id INTEGER REFERENCES channels(id) ON DELETE CASCADE,
    subscribed_at TIMESTAMP DEFAULT NOW(),
    PRIMARY KEY (user_id, channel_id)
);

-- Groups (with optional nesting via parent_group_id)
CREATE TABLE groups (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    description TEXT,
    owner_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    parent_group_id INTEGER REFERENCES groups(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Group Members
CREATE TABLE group_members (
    group_id INTEGER REFERENCES groups(id) ON DELETE CASCADE,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    joined_at TIMESTAMP DEFAULT NOW(),
    PRIMARY KEY (group_id, user_id)
);

-- Group Messages
CREATE TABLE group_messages (
    id SERIAL PRIMARY KEY,
    group_id INTEGER REFERENCES groups(id) ON DELETE CASCADE,
    sender_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    content TEXT,
    media_url TEXT,
    deleted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Reactions
CREATE TABLE reactions (
    id SERIAL PRIMARY KEY,
    message_type TEXT CHECK (message_type IN ('private', 'channel', 'group')),
    message_id INTEGER NOT NULL,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    emoji TEXT NOT NULL,
    reacted_at TIMESTAMP DEFAULT NOW()
);

-- Attachments (can be associated with any message)
CREATE TABLE attachments (
    id SERIAL PRIMARY KEY,
    message_type TEXT CHECK (message_type IN ('private', 'channel', 'group')),
    message_id INTEGER NOT NULL,
    file_url TEXT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT NOW()
);

-- Sample data
INSERT INTO users (username, display_name, avatar_url, created_at) VALUES
('alice', 'Alice', 'https://example.com/avatar1.png', '2025-06-15 13:23:25'),
('bob', 'Bob', 'https://example.com/avatar2.png', '2025-06-15 13:23:25'),
('carol', 'Carol', 'https://example.com/avatar3.png', '2025-06-15 13:23:25');

INSERT INTO messages (sender_id, receiver_id, content, created_at) VALUES
(1, 2, 'Hi Bob!', '2025-06-15 13:23:25'),
(2, 1, 'Hey Alice!', '2025-06-15 13:23:25');

INSERT INTO channels (owner_id, name, description, created_at) VALUES
(1, 'TechNews', 'Latest tech updates', '2025-06-15 13:23:25');

INSERT INTO channel_messages (channel_id, sender_id, content, created_at) VALUES
(1, 1, 'Welcome to TechNews!', '2025-06-15 13:23:25');

INSERT INTO channel_subscriptions (user_id, channel_id, subscribed_at) VALUES
(2, 1, '2025-06-15 13:23:25'),
(3, 1, '2025-06-15 13:23:25');

INSERT INTO groups (name, description, owner_id, parent_group_id, created_at) VALUES
('Отдых', 'Группа для обсуждения отдыха', 1, NULL, '2025-06-15 13:23:25'),
('Море', 'Топик о море', 1, 1, '2025-06-15 13:23:25'),
('Горы', 'Топик о горах', 1, 1, '2025-06-15 13:23:25');

INSERT INTO group_members (group_id, user_id, joined_at) VALUES
(1, 2, '2025-06-15 13:23:25'),
(2, 2, '2025-06-15 13:23:25'),
(3, 3, '2025-06-15 13:23:25');

INSERT INTO group_messages (group_id, sender_id, content, created_at) VALUES
(2, 2, 'Люблю море!', '2025-06-15 13:23:25'),
(3, 3, 'Горы прекрасны!', '2025-06-15 13:23:25');

INSERT INTO reactions (message_type, message_id, user_id, emoji, reacted_at) VALUES
('private', 1, 2, '❤️', '2025-06-15 13:23:25'),
('group', 1, 3, '🔥', '2025-06-15 13:23:25');

INSERT INTO attachments (message_type, message_id, file_url, uploaded_at) VALUES
('group', 1, 'https://example.com/sea.jpg', '2025-06-15 13:23:25');


-- Message edits (for tracking history)
CREATE TABLE message_edits (
    id SERIAL PRIMARY KEY,
    message_type TEXT CHECK (message_type IN ('private', 'channel', 'group')),
    message_id INTEGER NOT NULL,
    editor_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    old_content TEXT,
    new_content TEXT,
    edited_at TIMESTAMP DEFAULT NOW()
);

-- Channel roles and permissions
CREATE TABLE channel_roles (
    id SERIAL PRIMARY KEY,
    channel_id INTEGER REFERENCES channels(id) ON DELETE CASCADE,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    role TEXT CHECK (role IN ('owner', 'moderator', 'viewer')) DEFAULT 'viewer',
    granted_at TIMESTAMP DEFAULT NOW()
);

-- Group roles and permissions
CREATE TABLE group_roles (
    id SERIAL PRIMARY KEY,
    group_id INTEGER REFERENCES groups(id) ON DELETE CASCADE,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    role TEXT CHECK (role IN ('owner', 'moderator', 'member')) DEFAULT 'member',
    granted_at TIMESTAMP DEFAULT NOW()
);

-- Comments (can be on any message)
CREATE TABLE comments (
    id SERIAL PRIMARY KEY,
    message_type TEXT CHECK (message_type IN ('private', 'channel', 'group')),
    message_id INTEGER NOT NULL,
    commenter_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Sample role data
INSERT INTO channel_roles (channel_id, user_id, role, granted_at) VALUES
(1, 1, 'owner', '2025-06-15 13:30:47'),
(1, 2, 'viewer', '2025-06-15 13:30:47');

INSERT INTO group_roles (group_id, user_id, role, granted_at) VALUES
(1, 1, 'owner', '2025-06-15 13:30:47'),
(1, 2, 'member', '2025-06-15 13:30:47');

-- Sample edit and comment
INSERT INTO message_edits (message_type, message_id, editor_id, old_content, new_content, edited_at) VALUES
('group', 1, 2, 'Люблю море!', 'Обожаю море!', '2025-06-15 13:30:47');

INSERT INTO comments (message_type, message_id, commenter_id, content, created_at) VALUES
('group', 1, 3, 'Согласен, море класс!', '2025-06-15 13:30:47');
