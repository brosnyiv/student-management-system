<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monaco Institute Student Portal - Messages</title>
    <link rel="stylesheet" href="dash.css">
    <style>
        /* Message-specific styles */
        .messages-container {
            display: flex;
            height: calc(100vh - 150px);
            gap: 20px;
        }
        
        .contacts-list {
            width: 280px;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .contacts-header {
            background-color: #2c3e50;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .contacts-search {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .contacts-search input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 14px;
        }
        
        .contact-list {
            overflow-y: auto;
            height: calc(100% - 110px);
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.2s;
        }
        
        .contact-item:hover, .contact-item.active {
            background-color: #f5f7fa;
        }
        
        .contact-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #3498db;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
            flex-shrink: 0;
        }
        
        .contact-info {
            flex: 1;
            overflow: hidden;
        }
        
        .contact-name {
            font-weight: 500;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .contact-preview {
            font-size: 13px;
            color: #7f8c8d;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .contact-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            min-width: 45px;
        }
        
        .contact-time {
            font-size: 12px;
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .unread-badge {
            background-color: #3498db;
            color: white;
            border-radius: 50%;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
        
        .chat-window {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .chat-header {
            background-color: #2c3e50;
            color: white;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .chat-contact {
            display: flex;
            align-items: center;
        }
        
        .chat-actions {
            display: flex;
            gap: 15px;
        }
        
        .chat-action {
            cursor: pointer;
            opacity: 0.8;
            transition: opacity 0.2s;
        }
        
        .chat-action:hover {
            opacity: 1;
        }
        
        .messages-area {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background-color: #f5f7fa;
        }
        
        .message {
            max-width: 75%;
            padding: 10px 15px;
            border-radius: 18px;
            margin-bottom: 10px;
            position: relative;
            word-wrap: break-word;
        }
        
        .message-incoming {
            background-color: #e5e5ea;
            color: #333;
            align-self: flex-start;
            border-bottom-left-radius: 5px;
            margin-right: auto;
        }
        
        .message-outgoing {
            background-color: #3498db;
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 5px;
            margin-left: auto;
        }
        
        .message-flagged {
            border: 2px solid #e74c3c;
        }
        
        .message-sender {
            font-weight: 500;
            font-size: 13px;
            margin-bottom: 5px;
        }
        
        .message-time {
            font-size: 11px;
            opacity: 0.7;
            text-align: right;
            margin-top: 5px;
        }
        
        .message-warning {
            color: #e74c3c;
            font-size: 12px;
            text-align: right;
            margin-top: 3px;
            font-style: italic;
        }
        
        .chat-input-area {
            display: flex;
            padding: 15px;
            background-color: #f9f9f9;
            border-top: 1px solid #eee;
        }
        
        .chat-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 20px;
            margin-right: 10px;
            font-size: 14px;
        }
        
        .send-btn {
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }
        
        .send-btn:hover {
            background-color: #2980b9;
        }
        
        .system-message {
            text-align: center;
            max-width: 80%;
            margin: 15px auto;
            padding: 8px 15px;
            background-color: #f8f9fa;
            border-radius: 10px;
            font-size: 13px;
            color: #7f8c8d;
        }
        
        .filter-options {
            display: flex;
            gap: 10px;
            padding: 10px;
        }
        
        .filter-option {
            background-color: #f1f1f1;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .filter-option.active {
            background-color: #3498db;
            color: white;
        }
        
        /* New message popup */
        .new-message-popup {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            padding: 20px;
            width: 350px;
            z-index: 1000;
            display: none;
        }
        
        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .popup-header h3 {
            margin: 0;
        }
        
        .popup-close {
            cursor: pointer;
            font-size: 18px;
        }
        
        .language-warning {
            background-color: #ffebee;
            border-left: 4px solid #e74c3c;
            padding: 10px 15px;
            margin: 10px 0;
            border-radius: 0 5px 5px 0;
            display: flex;
            align-items: center;
        }
        
        .warning-icon {
            color: #e74c3c;
            margin-right: 10px;
            font-size: 18px;
        }
        
        .contact-badge {
            background-color: #34495e;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="sidebar-logo">
                <img src="logo.png" alt="Monaco Institute Logo" class="logo-img">
            </div>
            <div class="sidebar-header">
                <h2>Monaco Institute</h2>
                <p>Empowering Professional Skills</p>
            </div>
            <ul class="nav-menu">
                <li class="nav-item" onclick="window.location.href='dash.html'">Dashboard</li>
                <li class="nav-item" onclick="window.location.href='course.html'">My Courses</li>
                <li class="nav-item" onclick="window.location.href='asignments.html'">Assignments</li>
                <li class="nav-item" onclick="window.location.href='results.html'">Results</li>
                <li class="nav-item" onclick="window.location.href='attendence.html'">Attendance</li>
                <li class="nav-item" onclick="window.location.href='payments.html'">Payments</li>
                <li class="nav-item" onclick="window.location.href='drop semester.html'">Drop Semester</li>
                <li class="nav-item" onclick="window.location.href='notices.html'">Notices</li>
                <li class="nav-item" onclick="window.location.href='messages.html'">Messages <span class="badge">3</span></li>
                <li class="logout-item" onclick="window.location.href='login.html'">Log Out</li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <div class="header-left">
                    <h1>Messages</h1>
                    <span class="header-date" id="current-date">Thursday, April 11, 2025</span>
                </div>
                <div class="search-bar">
                    <input type="text" placeholder="Search...">
                </div>
                <div class="user-actions">
                    <i class="settings-icon">⚙️</i>
                </div>
            </div>
            
            <!-- Messages Section -->
            <div id="messages" class="content-section">
                <div class="section">
                    <div class="section-header">
                        <h3>Communication Center</h3>
                        <div class="action-buttons">
                            <button class="primary-btn" onclick="openNewMessagePopup()">New Message</button>
                        </div>
                    </div>
                    
                    <div class="messages-container">
                        <!-- Contacts List -->
                        <div class="contacts-list">
                            <div class="contacts-header">
                                <h3>Conversations</h3>
                                <span class="badge">3</span>
                            </div>
                            
                            <div class="contacts-search">
                                <input type="text" placeholder="Search contacts...">
                            </div>
                            
                            <div class="filter-options">
                                <div class="filter-option active">All</div>
                                <div class="filter-option">Students</div>
                                <div class="filter-option">Faculty</div>
                                <div class="filter-option">Staff</div>
                            </div>
                            
                            <div class="contact-list">
                                <div class="contact-item active" onclick="loadConversation('user1')">
                                    <div class="contact-avatar" style="background-color: #3498db;">MJ</div>
                                    <div class="contact-info">
                                        <div class="contact-name">Maria Johnson <span class="contact-badge">Student</span></div>
                                        <div class="contact-preview">Can you share your notes from yesterday's lecture?</div>
                                    </div>
                                    <div class="contact-meta">
                                        <div class="contact-time">10:45 AM</div>
                                        <div class="unread-badge">2</div>
                                    </div>
                                </div>
                                
                                <div class="contact-item" onclick="loadConversation('user2')">
                                    <div class="contact-avatar" style="background-color: #2ecc71;">DW</div>
                                    <div class="contact-info">
                                        <div class="contact-name">Dr. Wilson <span class="contact-badge">Faculty</span></div>
                                        <div class="contact-preview">Please submit your assignment by Friday...</div>
                                    </div>
                                    <div class="contact-meta">
                                        <div class="contact-time">Yesterday</div>
                                        <div class="unread-badge">1</div>
                                    </div>
                                </div>
                                
                                <div class="contact-item" onclick="loadConversation('user3')">
                                    <div class="contact-avatar" style="background-color: #e67e22;">CS</div>
                                    <div class="contact-info">
                                        <div class="contact-name">CS301 Group <span class="contact-badge">Group</span></div>
                                        <div class="contact-preview">Kevin: Has anyone started the project yet?</div>
                                    </div>
                                    <div class="contact-meta">
                                        <div class="contact-time">Apr 10</div>
                                    </div>
                                </div>
                                
                                <div class="contact-item" onclick="loadConversation('user4')">
                                    <div class="contact-avatar" style="background-color: #9b59b6;">AT</div>
                                    <div class="contact-info">
                                        <div class="contact-name">Academic Tutor <span class="contact-badge">Staff</span></div>
                                        <div class="contact-preview">Your appointment has been confirmed for...</div>
                                    </div>
                                    <div class="contact-meta">
                                        <div class="contact-time">Apr 9</div>
                                    </div>
                                </div>
                                
                                <div class="contact-item" onclick="loadConversation('user5')">
                                    <div class="contact-avatar" style="background-color: #1abc9c;">RL</div>
                                    <div class="contact-info">
                                        <div class="contact-name">Rachel Lewis <span class="contact-badge">Student</span></div>
                                        <div class="contact-preview">Are we still meeting at the library tomorrow?</div>
                                    </div>
                                    <div class="contact-meta">
                                        <div class="contact-time">Apr 8</div>
                                    </div>
                                </div>
                                
                                <div class="contact-item" onclick="loadConversation('user6')">
                                    <div class="contact-avatar" style="background-color: #f39c12;">IT</div>
                                    <div class="contact-info">
                                        <div class="contact-name">IT Support <span class="contact-badge">Staff</span></div>
                                        <div class="contact-preview">Your ticket #45783 has been resolved.</div>
                                    </div>
                                    <div class="contact-meta">
                                        <div class="contact-time">Apr 5</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Chat Window -->
                        <div class="chat-window">
                            <div class="chat-header">
                                <div class="chat-contact">
                                    <div class="contact-avatar" style="background-color: #3498db; width: 40px; height: 40px; margin-right: 10px;">MJ</div>
                                    <div>
                                        <h3>Maria Johnson</h3>
                                        <small>STU2025042 • Computer Science, Year 3</small>
                                    </div>
                                </div>
                                <div class="chat-actions">
                                    <div class="chat-action">📞</div>
                                    <div class="chat-action">📹</div>
                                    <div class="chat-action">ℹ️</div>
                                </div>
                            </div>
                            
                            <div class="messages-area">
                                <div class="system-message">Conversation started on April 10, 2025</div>
                                
                                <div style="display: flex; margin-bottom: 15px;">
                                    <div class="contact-avatar" style="background-color: #3498db; width: 30px; height: 30px; font-size: 12px; margin-right: 8px;">MJ</div>
                                    <div style="flex: 1;">
                                        <div class="message-sender">Maria Johnson • 10:15 AM</div>
                                        <div class="message message-incoming">
                                            Hi John! Did you take notes during yesterday's Data Structures lecture? I had to leave early for a doctor's appointment.
                                        </div>
                                    </div>
                                </div>
                                
                                <div style="display: flex; flex-direction: row-reverse; margin-bottom: 15px;">
                                    <div class="contact-avatar" style="background-color: #2c3e50; width: 30px; height: 30px; font-size: 12px; margin-left: 8px;">JS</div>
                                    <div style="flex: 1;">
                                        <div class="message-sender" style="text-align: right;">You • 10:20 AM</div>
                                        <div class="message message-outgoing">
                                            Hey Maria! Yes, I have them. The professor covered binary search trees and AVL rotations.
                                        </div>
                                    </div>
                                </div>
                                
                                <div style="display: flex; margin-bottom: 15px;">
                                    <div class="contact-avatar" style="background-color: #3498db; width: 30px; height: 30px; font-size: 12px; margin-right: 8px;">MJ</div>
                                    <div style="flex: 1;">
                                        <div class="message-sender">Maria Johnson • 10:30 AM</div>
                                        <div class="message message-incoming">
                                            That's exactly what I was afraid of missing! Any chance you could share your notes with me? I'd really appreciate it.
                                        </div>
                                    </div>
                                </div>
                                
                                <div style="display: flex; flex-direction: row-reverse; margin-bottom: 15px;">
                                    <div class="contact-avatar" style="background-color: #2c3e50; width: 30px; height: 30px; font-size: 12px; margin-left: 8px;">JS</div>
                                    <div style="flex: 1;">
                                        <div class="message-sender" style="text-align: right;">You • 10:35 AM</div>
                                        <div class="message message-outgoing">
                                            Of course! I'll scan them and attach them here. Give me a few minutes.
                                        </div>
                                    </div>
                                </div>
                                
                                <div style="display: flex; margin-bottom: 15px;">
                                    <div class="contact-avatar" style="background-color: #3498db; width: 30px; height: 30px; font-size: 12px; margin-right: 8px;">MJ</div>
                                    <div style="flex: 1;">
                                        <div class="message-sender">Maria Johnson • 10:42 AM</div>
                                        <div class="message message-incoming message-flagged">
                                            That would be so damn helpful! You're saving my a$$. I was freaking out about missing this stuff.
                                        </div>
                                        <div class="message-warning">⚠️ This message contains potentially inappropriate language</div>
                                    </div>
                                </div>
                                
                                <div style="display: flex; margin-bottom: 15px;">
                                    <div class="contact-avatar" style="background-color: #3498db; width: 30px; height: 30px; font-size: 12px; margin-right: 8px;">MJ</div>
                                    <div style="flex: 1;">
                                        <div class="message-sender">Maria Johnson • 10:43 AM</div>
                                        <div class="message message-incoming">
                                            Sorry about the language! I'm just really stressed about the upcoming midterm.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="system-message">Maria is typing...</div>
                            </div>
                            
                            <div class="chat-input-area">
                                <input type="text" class="chat-input" placeholder="Type a message..." id="messageInput">
                                <button class="send-btn" onclick="sendMessage()">➤</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- New Message Popup -->
            <div class="new-message-popup" id="newMessagePopup">
                <div class="popup-header">
                    <h3>New Message</h3>
                    <div class="popup-close" onclick="closeNewMessagePopup()">✕</div>
                </div>
                
                <div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">To:</label>
                        <input type="text" class="chat-input" placeholder="Search for a student, faculty, or staff member...">
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Message:</label>
                        <textarea class="chat-input" style="width: 100%; height: 100px; resize: none;" placeholder="Type your message here..."></textarea>
                    </div>
                    
                    <div class="language-warning">
                        <span class="warning-icon">⚠️</span>
                        <div>
                            <strong>Remember:</strong> All messages are monitored for inappropriate language and conduct according to the Student Code of Conduct.
                        </div>
                    </div>
                    
                    <div style="text-align: right; margin-top: 15px;">
                        <button class="secondary-btn" onclick="closeNewMessagePopup()">Cancel</button>
                        <button class="primary-btn" style="margin-left: 10px;">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Display current date
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        
        // Function to show different sections
        function showSection(sectionId) {
            // Hide all sections
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(section => {
                section.style.display = 'none';
            });
            
            // Show the selected section
            document.getElementById(sectionId).style.display = 'block';
            
            // Update active nav item
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                item.classList.remove('active');
                if(item.getAttribute('onclick').includes(sectionId)) {
                    item.classList.add('active');
                }
            });
        }
        
        // Function to handle logout
        function logout() {
            alert('Logging out...');
            // In a real application, this would handle the logout process
        }
        
        // Function to load a conversation
        function loadConversation(userId) {
            // Update active contact
            const contactItems = document.querySelectorAll('.contact-item');
            contactItems.forEach(item => {
                item.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
            
            // In a real application, this would load conversation data
            console.log(`Loading conversation with user ID: ${userId}`);
        }
        
        // Function to send a message
        function sendMessage() {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            
            if (message) {
                // Check for inappropriate language
                const inappropriateWords = ['damn', 'ass', 'crap', 'hell', 'shit', 'fuck'];
                let containsInappropriate = false;
                
                inappropriateWords.forEach(word => {
                    if (message.toLowerCase().includes(word)) {
                        containsInappropriate = true;
                    }
                });
                
                // Create message element
                const messagesArea = document.querySelector('.messages-area');
                const messageDiv = document.createElement('div');
                messageDiv.style.display = 'flex';
                messageDiv.style.flexDirection = 'row-reverse';
                messageDiv.style.marginBottom = '15px';
                
                const timestamp = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                
                messageDiv.innerHTML = `
                    <div class="contact-avatar" style="background-color: #2c3e50; width: 30px; height: 30px; font-size: 12px; margin-left: 8px;">JS</div>
                    <div style="flex: 1;">
                        <div class="message-sender" style="text-align: right;">You • ${timestamp}</div>
                        <div class="message message-outgoing ${containsInappropriate ? 'message-flagged' : ''}">
                            ${message}
                        </div>
                        ${containsInappropriate ? '<div class="message-warning">⚠️ This message contains potentially inappropriate language</div>' : ''}
                    </div>
                `;
                
                messagesArea.appendChild(messageDiv);
                
                // Clear input and scroll to bottom
                messageInput.value = '';
                messagesArea.scrollTop = messagesArea.scrollHeight;
                
                // Show notification if inappropriate language was used
                if (containsInappropriate) {
                    setTimeout(() => {
                        const systemMessage = document.createElement('div');
                        systemMessage.className = 'system-message';
                        systemMessage.innerHTML = '⚠️ Please be mindful of your language according to the Student Code of Conduct';
                        messagesArea.appendChild(systemMessage);
                        messagesArea.scrollTop = messagesArea.scrollHeight;
                    }, 500);
                }
                
                // Simulate reply after a short delay
                setTimeout(() => {
                    const typingDiv = document.createElement('div');
                    typingDiv.className = 'system-message';
                    typingDiv.textContent = 'Maria is typing...';
                    messagesArea.appendChild(typingDiv);
                    messagesArea.scrollTop = messagesArea.scrollHeight;
                    
                    setTimeout(() => {
                        messagesArea.removeChild(typingDiv);
                        
                        const replyDiv = document.createElement('div');
                        replyDiv.style.display = 'flex';
                        replyDiv.style.marginBottom = '15px';
                        
                        const replyTimestamp = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                        
                        replyDiv.innerHTML = `
                            <div class="contact-avatar" style="background-color: #3498db; width: 30px; height: 30px; font-size: 12px; margin-right: 8px;">MJ</div>
                            <div style="flex: 1;">
                                <div class="message-sender">Maria Johnson • ${replyTimestamp}</div>
                                <div class="message message-incoming">
                                    Thanks so much! You're a lifesaver. I'll definitely return the favor sometime.
                                </div>
                            </div>
                        `;
                        
                        messagesArea.appendChild(replyDiv);
                        messagesArea.scrollTop = messagesArea.scrollHeight;
                    }, 2000);
                }, 1000);
            }
        }
        
        // Function to open new message popup
        function openNewMessagePopup() {
            document.getElementById('newMessagePopup').style.display = 'block';
        }
        
        // Function to close new message popup
        function closeNewMessagePopup() {
            document.getElementById('newMessagePopup').style.display = 'none';
        }
        
        // Event listener for Enter key in message input
        document.getElementById('messageInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
        
        // Make contacts list items clickable
        document.querySelectorAll('.contact-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.contact-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Make filter options clickable
        document.querySelectorAll('.filter-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.filter-option').forEach(o => o.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>