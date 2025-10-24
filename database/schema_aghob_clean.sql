-- Assemblies of God Church House of Bread Database Schema
-- Clean version without duplicates

CREATE DATABASE IF NOT EXISTS aghob CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE aghob;

-- Site Settings
CREATE TABLE site_settings (
  id int(11) NOT NULL AUTO_INCREMENT,
  site_name varchar(255) DEFAULT 'Assemblies of God Church House of Bread',
  site_tagline varchar(255) DEFAULT 'Building Faith, Transforming Lives',
  site_email varchar(255) DEFAULT NULL,
  site_phone varchar(20) DEFAULT NULL,
  site_address text DEFAULT NULL,
  facebook_url varchar(255) DEFAULT NULL,
  instagram_url varchar(255) DEFAULT NULL,
  youtube_url varchar(255) DEFAULT NULL,
  twitter_url varchar(255) DEFAULT NULL,
  linkedin_url varchar(255) DEFAULT NULL,
  whatsapp_url varchar(255) DEFAULT NULL,
  live_stream_url varchar(500) DEFAULT NULL,
  service_times varchar(255) DEFAULT NULL,
  service_location varchar(255) DEFAULT NULL,
  about_text text DEFAULT NULL,
  mission_statement text DEFAULT NULL,
  vision_statement text DEFAULT NULL,
  welcome_message text DEFAULT NULL,
  contact_email varchar(255) DEFAULT NULL,
  contact_phone varchar(20) DEFAULT NULL,
  contact_address text DEFAULT NULL,
  map_embed_code text DEFAULT NULL,
  meta_description text DEFAULT NULL,
  meta_keywords text DEFAULT NULL,
  logo varchar(255) DEFAULT NULL,
  favicon varchar(255) DEFAULT NULL,
  hero_image varchar(255) DEFAULT NULL,
  hero_title varchar(255) DEFAULT NULL,
  hero_subtitle text DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admins
CREATE TABLE admins (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(50) NOT NULL UNIQUE,
  password varchar(255) NOT NULL,
  full_name varchar(100) NOT NULL,
  email varchar(100) NOT NULL,
  phone varchar(20) DEFAULT NULL,
  role varchar(50) DEFAULT 'admin',
  is_active tinyint(1) DEFAULT 1,
  last_login datetime DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pastors (replaced presbyters)
CREATE TABLE pastors (
  id int(11) NOT NULL AUTO_INCREMENT,
  full_name varchar(255) NOT NULL,
  position enum('senior_pastor','assistant_pastor','children_pastor') NOT NULL,
  email varchar(255) DEFAULT NULL,
  phone varchar(20) DEFAULT NULL,
  photo varchar(255) DEFAULT NULL,
  bio text DEFAULT NULL,
  credentials varchar(255) DEFAULT NULL,
  ordination_date date DEFAULT NULL,
  is_active tinyint(1) DEFAULT 1,
  display_order int(11) DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Events
CREATE TABLE events (
  id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  slug varchar(255) NOT NULL,
  description text DEFAULT NULL,
  event_date date NOT NULL,
  event_time time DEFAULT NULL,
  end_date date DEFAULT NULL,
  end_time time DEFAULT NULL,
  location varchar(255) DEFAULT NULL,
  address text DEFAULT NULL,
  featured_image varchar(255) DEFAULT NULL,
  contact_person varchar(255) DEFAULT NULL,
  contact_phone varchar(20) DEFAULT NULL,
  contact_email varchar(255) DEFAULT NULL,
  registration_required tinyint(1) DEFAULT 0,
  registration_link varchar(500) DEFAULT NULL,
  max_attendees int(11) DEFAULT NULL,
  event_type enum('conference','seminar','workshop','outreach','celebration','training','youth','worship','prayer','other') DEFAULT 'other',
  cost varchar(100) DEFAULT NULL,
  is_featured tinyint(1) DEFAULT 0,
  is_active tinyint(1) DEFAULT 1,
  created_by int(11) DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (id),
  UNIQUE KEY slug (slug),
  KEY event_date (event_date),
  KEY event_type (event_type),
  KEY is_featured (is_featured),
  KEY is_active (is_active),
  KEY created_by (created_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sermons
CREATE TABLE sermons (
  id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  slug varchar(255) NOT NULL,
  description text DEFAULT NULL,
  speaker varchar(255) NOT NULL,
  sermon_date date NOT NULL,
  scripture_reference varchar(255) DEFAULT NULL,
  series_name varchar(255) DEFAULT NULL,
  category varchar(100) DEFAULT NULL,
  duration int(11) DEFAULT NULL COMMENT 'Duration in minutes',
  audio_file varchar(255) DEFAULT NULL,
  audio_size bigint(20) DEFAULT NULL COMMENT 'File size in bytes',
  video_url varchar(500) DEFAULT NULL,
  pdf_file varchar(255) DEFAULT NULL,
  pdf_size bigint(20) DEFAULT NULL COMMENT 'File size in bytes',
  thumbnail varchar(255) DEFAULT NULL,
  transcript text DEFAULT NULL,
  notes text DEFAULT NULL,
  is_featured tinyint(1) DEFAULT 0,
  is_active tinyint(1) DEFAULT 1,
  view_count int(11) DEFAULT 0,
  created_by int(11) DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (id),
  UNIQUE KEY slug (slug),
  KEY sermon_date (sermon_date),
  KEY speaker (speaker),
  KEY category (category),
  KEY is_featured (is_featured),
  KEY is_active (is_active),
  KEY created_by (created_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ministries
CREATE TABLE ministries (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  slug varchar(255) NOT NULL,
  description text DEFAULT NULL,
  leader_name varchar(255) DEFAULT NULL,
  leader_phone varchar(20) DEFAULT NULL,
  leader_email varchar(255) DEFAULT NULL,
  meeting_day varchar(50) DEFAULT NULL,
  meeting_time varchar(50) DEFAULT NULL,
  meeting_location varchar(255) DEFAULT NULL,
  activities text DEFAULT NULL,
  image varchar(255) DEFAULT NULL,
  is_active tinyint(1) DEFAULT 1,
  display_order int(11) DEFAULT 0,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (id),
  UNIQUE KEY slug (slug),
  KEY is_active (is_active),
  KEY display_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Prayer Requests
CREATE TABLE prayer_requests (
  id int(11) NOT NULL AUTO_INCREMENT,
  full_name varchar(255) NOT NULL,
  email varchar(255) DEFAULT NULL,
  phone varchar(20) DEFAULT NULL,
  prayer_request text NOT NULL,
  is_anonymous tinyint(1) DEFAULT 0,
  status enum('pending','praying','answered') DEFAULT 'pending',
  ip_address varchar(45) DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (id),
  KEY status (status),
  KEY created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contact Submissions
CREATE TABLE contact_submissions (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  phone varchar(20) DEFAULT NULL,
  subject varchar(255) DEFAULT NULL,
  message text NOT NULL,
  status enum('new','read','responded','archived') DEFAULT 'new',
  ip_address varchar(45) DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (id),
  KEY status (status),
  KEY created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Newsletter Subscribers
CREATE TABLE newsletter_subscribers (
  id int(11) NOT NULL AUTO_INCREMENT,
  email varchar(255) NOT NULL UNIQUE,
  full_name varchar(255) DEFAULT NULL,
  status enum('active','inactive','unsubscribed') DEFAULT 'active',
  unsubscribe_token varchar(255) DEFAULT NULL,
  ip_address varchar(45) DEFAULT NULL,
  subscribed_at timestamp NOT NULL DEFAULT current_timestamp(),
  updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (id),
  UNIQUE KEY email (email),
  KEY status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Activity Logs
CREATE TABLE activity_logs (
  id int(11) NOT NULL AUTO_INCREMENT,
  admin_id int(11) DEFAULT NULL,
  action varchar(100) NOT NULL,
  table_name varchar(100) DEFAULT NULL,
  record_id int(11) DEFAULT NULL,
  description text DEFAULT NULL,
  ip_address varchar(45) DEFAULT NULL,
  user_agent text DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id),
  KEY admin_id (admin_id),
  KEY action (action),
  KEY table_name (table_name),
  KEY created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default data
INSERT INTO site_settings (site_name, site_tagline, meta_description, meta_keywords) VALUES
('Assemblies of God Church House of Bread', 'Building Faith, Transforming Lives',
'Welcome to Assemblies of God Church House of Bread - Building Faith, Transforming Lives in our community.',
'church,AGC,House of Bread,worship,community,faith');

INSERT INTO admins (username, password, full_name, email, role) VALUES
('admin', 'admin', 'Administrator', 'admin@aghob.org', 'admin');

INSERT INTO ministries (name, slug, description, display_order, is_active) VALUES
('Praise & Worship', 'praise-worship', 'Join us in lifting up the name of Jesus through powerful worship and praise.', 1, 1),
('Children Ministry', 'children-ministry', 'Nurturing the faith of our children through Bible-based teaching and fun activities.', 2, 1),
('Youth Ministry', 'youth-ministry', 'Empowering young people to live for Christ and make a difference in their world.', 3, 1),
('Women Ministry', 'women-ministry', 'Building community and faith among women of all ages through fellowship and service.', 4, 1),
('Men Ministry', 'men-ministry', 'Strengthening men in their faith and leadership through brotherhood and accountability.', 5, 1),
('Outreach Ministry', 'outreach-ministry', 'Reaching our community with the love of Christ through service and evangelism.', 6, 1);

INSERT INTO pastors (full_name, position, email, bio, display_order, is_active) VALUES
('Rev. John Smith', 'senior_pastor', 'senior.pastor@aghob.org', 'Senior Pastor with over 20 years of ministry experience, dedicated to shepherding the House of Bread family.', 1, 1),
('Rev. Sarah Johnson', 'assistant_pastor', 'assistant.pastor@aghob.org', 'Assistant Pastor passionate about discipleship and spiritual growth.', 2, 1),
('Rev. Michael Davis', 'children_pastor', 'children.pastor@aghob.org', 'Children\'s Pastor committed to mentoring the next generation of leaders.', 3, 1);