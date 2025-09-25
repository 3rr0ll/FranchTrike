<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FranchTrike</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'primary-navy': '#1D2761',
            'primary-gold': '#FFD700',
            'accent-purple': '#5E2D79',
            'accent-red': '#E63946',
            'accent-green': '#2A9D8F',
            'white': '#FFFFFF',
          }
        }
      }
    }
  </script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="css/components.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

</head>

<body class="bg-white text-primary-navy font-['Inter']">

  <!-- Navbar -->
  <header class="bg-primary-navy text-white shadow-lg fixed w-full z-50">

    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
      <div class="flex items-center gap-2">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">

        <h1 class="text-xl font-bold">FranchTrike</h1>
      </div>

      <!-- Menu Button (for small screens) -->
      <div class="md:hidden flex items-center gap-4">
        <button id="menu-btn" class="text-white hover:text-primary-gold focus:outline-none transition-colors">
          <i class="bi bi-list text-2xl"></i>
        </button>
      </div>

      <!-- Navigation for Desktop and Tablet -->
      <nav class="hidden md:flex gap-8 text-sm font-medium">
        <a href="#features" class="hover:text-primary-gold transition-colors">Features</a>
        <a href="#about" class="hover:text-primary-gold transition-colors">About</a>
        <a href="#contact" class="hover:text-primary-gold transition-colors">Contact</a>
        <a href="#faq" class="hover:text-primary-gold transition-colors">FAQ</a>
      </nav>

      <nav class="hidden md:flex gap-4 items-center justify-end">
        <a
          href="{{ route('register') }}"
          class="bg-primary-gold text-primary-navy font-semibold px-6 py-2 rounded-full hover:bg-yellow-400 transition-colors text-sm">
          Register
        </a>
        <a
          href="{{ route('login') }}"
          class="border-2 border-white text-white font-semibold px-6 py-2 rounded-full hover:bg-white hover:text-primary-navy transition-colors text-sm">
          Login
        </a>
      </nav>
    </div>

    <!-- Mobile Navigation -->
    <div id="mobile-nav" class="md:hidden hidden bg-primary-navy text-white p-6 border-t border-white/10">
      <nav class="flex flex-col gap-6 text-sm font-medium">
        <a href="#features" class="hover:text-primary-gold transition-colors">Features</a>
        <a href="#about" class="hover:text-primary-gold transition-colors">About</a>
        <a href="#contact" class="hover:text-primary-gold transition-colors">Contact</a>
        <a href="#faq" class="hover:text-primary-gold transition-colors">FAQ</a>
        <div class="flex flex-col gap-4 pt-4 border-t border-white/10">
          <a href="register.html" class="bg-primary-gold text-primary-navy font-semibold px-6 py-3 rounded-full hover:bg-yellow-400 transition-colors text-center">
            Register
          </a>
          <a href="login.html" class="border-2 border-white text-white font-semibold px-6 py-3 rounded-full hover:bg-white hover:text-primary-navy transition-colors text-center">
            Login
          </a>
        </div>
      </nav>
    </div>
  </header>
  @if (Route::has('login'))
  <div class="h-14.5 hidden lg:block"></div>
  @endif
  <!-- Hero Section -->
  <section class="pt-32 pb-20 bg-gradient-to-b from-white to-gray-50">
    <div class="container mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-12">
      <div class="max-w-xl">
        <h2 class="text-5xl font-bold mb-6 leading-tight">Tricycle Franchising in Padre Garcia</h2>
        <p class="text-lg mb-8 text-gray-700">Apply, renew, and track your franchise status—all in one place. FranchTrike makes the process faster and easier than ever before.</p>
      </div>
      <div class="relative flex items-center justify-center min-w-[80px] min-h-[80px]">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-120 w-120 object-contain mx-auto">
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="py-20 bg-white">
    <div class="container mx-auto px-6">
      <div class="text-center max-w-2xl mx-auto mb-16">
        <span class="text-primary-gold font-semibold">KEY FEATURES</span>
        <h3 class="text-3xl font-bold mt-2 mb-4">Everything you need to manage your franchise</h3>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="group p-8 rounded-2xl bg-white hover:bg-primary-navy hover:text-white transition-all duration-300 shadow-lg hover:shadow-xl">
          <div class="w-12 h-12 bg-primary-gold/20 group-hover:bg-white/20 rounded-xl flex items-center justify-center mb-6">
            <i class="bi bi-file-earmark text-2xl text-primary-navy group-hover:text-white"></i>
          </div>
          <h4 class="text-xl font-semibold mb-4">Online Application</h4>
          <p class="text-gray-600 group-hover:text-gray-300">Apply for new or renewal of franchise anytime, anywhere with our user-friendly interface.</p>
        </div>

        <div class="group p-8 rounded-2xl bg-white hover:bg-primary-navy hover:text-white transition-all duration-300 shadow-lg hover:shadow-xl">
          <div class="w-12 h-12 bg-primary-gold/20 group-hover:bg-white/20 rounded-xl flex items-center justify-center mb-6">
            <i class="bi bi-box text-2xl text-primary-navy group-hover:text-white"></i>
          </div>
          <h4 class="text-xl font-semibold mb-4">Document Upload</h4>
          <p class="text-gray-600 group-hover:text-gray-300">Submit all required documents digitally with secure cloud storage and verification.</p>
        </div>

        <div class="group p-8 rounded-2xl bg-white hover:bg-primary-navy hover:text-white transition-all duration-300 shadow-lg hover:shadow-xl">
          <div class="w-12 h-12 bg-primary-gold/20 group-hover:bg-white/20 rounded-xl flex items-center justify-center mb-6">
            <i class="bi bi-search text-2xl text-primary-navy group-hover:text-white"></i>
          </div>
          <h4 class="text-xl font-semibold mb-4">Real-Time Tracking</h4>
          <p class="text-gray-600 group-hover:text-gray-300">Monitor your application progress with detailed status updates and notifications.</p>
        </div>

        <div class="group p-8 rounded-2xl bg-white hover:bg-primary-navy hover:text-white transition-all duration-300 shadow-lg hover:shadow-xl">
          <div class="w-12 h-12 bg-primary-gold/20 group-hover:bg-white/20 rounded-xl flex items-center justify-center mb-6">
            <i class="bi bi-credit-card text-2xl text-primary-navy group-hover:text-white"></i>
          </div>
          <h4 class="text-xl font-semibold mb-4">Secure Payments</h4>
          <p class="text-gray-600 group-hover:text-gray-300">Process payments safely through multiple channels with instant digital receipts.</p>
        </div>

        <div class="group p-8 rounded-2xl bg-white hover:bg-primary-navy hover:text-white transition-all duration-300 shadow-lg hover:shadow-xl">
          <div class="w-12 h-12 bg-primary-gold/20 group-hover:bg-white/20 rounded-xl flex items-center justify-center mb-6">
            <i class="bi bi-bell text-2xl text-primary-navy group-hover:text-white"></i>
          </div>
          <h4 class="text-xl font-semibold mb-4">Smart Notifications</h4>
          <p class="text-gray-600 group-hover:text-gray-300">Stay informed with timely alerts about deadlines, approvals, and important updates.</p>
        </div>

        <div class="group p-8 rounded-2xl bg-white hover:bg-primary-navy hover:text-white transition-all duration-300 shadow-lg hover:shadow-xl">
          <div class="w-12 h-12 bg-primary-gold/20 group-hover:bg-white/20 rounded-xl flex items-center justify-center mb-6">
            <i class="bi bi-chat-square-dots text-2xl text-primary-navy group-hover:text-white"></i>
          </div>
          <h4 class="text-xl font-semibold mb-4">24/7 Chat Support</h4>
          <p class="text-gray-600 group-hover:text-gray-300">Get instant answers to your questions with our chatbot.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="py-20 bg-primary-navy text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('assets/pattern.svg')] opacity-10"></div>
    <div class="container mx-auto px-6 max-w-4xl relative">
      <div class="text-center">
        <span class="text-primary-gold font-semibold">ABOUT US</span>
        <h3 class="text-3xl font-bold mt-2 mb-6">Revolutionizing Tricycle Franchising</h3>
        <p class="text-lg leading-relaxed text-gray-300">
          FranchTrike is an innovative online system designed to transform tricycle franchising in Padre Garcia, Batangas.
          We combine cutting-edge technology with user-friendly design to create a seamless experience for both tricycle operators
          and local government officials. Our platform ensures transparency, efficiency, and better compliance through digital
          solutions that work for everyone.
        </p>
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section id="faq" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
      <div class="text-center max-w-2xl mx-auto mb-16">
        <span class="text-primary-gold font-semibold">FAQ</span>
        <h3 class="text-3xl font-bold mt-2 mb-4">Common Questions</h3>
        <p class="text-gray-600">Find answers to frequently asked questions about tricycle franchising</p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow">
          <h4 class="font-semibold text-xl mb-4">What documents do I need?</h4>
          <p class="text-gray-600">Valid ID, proof of ownership, OR/CR of the vehicle, barangay clearance, and tricycle photos. All documents can be easily uploaded through our portal.</p>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow">
          <h4 class="font-semibold text-xl mb-4">How long is processing?</h4>
          <p class="text-gray-600">Typical processing takes 5-7 working days after complete requirements submission and payment verification.</p>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow">
          <h4 class="font-semibold text-xl mb-4">Can I track my application?</h4>
          <p class="text-gray-600">Yes, track your application status in real-time through your personalized FranchTrike dashboard.</p>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow">
          <h4 class="font-semibold text-xl mb-4">What payment methods?</h4>
          <p class="text-gray-600">We accept GCash, bank transfers, credit/debit cards, and in-person payments at the Municipal Treasury Office.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section id="contact" class="py-20 bg-white">
    <div class="container mx-auto px-6 max-w-4xl">
      <div class="text-center mb-16">
        <span class="text-primary-gold font-semibold">CONTACT US</span>
        <h3 class="text-3xl font-bold mt-2 mb-4">Get in Touch</h3>
        <p class="text-gray-600">We're here to help with any questions about tricycle franchising</p>
      </div>

      <div class="bg-primary-navy text-white p-8 rounded-2xl shadow-xl">
        <div class="grid md:grid-cols-2 gap-8">
          <div class="space-y-6">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="bi bi-telephone text-xl"></i>
              </div>
              <div>
                <h4 class="font-semibold mb-1">Phone</h4>
                <p>(043) 515 7722</p>
              </div>
            </div>

            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="bi bi-geo-alt text-xl"></i>
              </div>
              <div>
                <h4 class="font-semibold mb-1">Address</h4>
                <p>Poblacion, Padre Garcia, Batangas</p>
              </div>
            </div>

            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="bi bi-envelope text-xl"></i>
              </div>
              <div>
                <h4 class="font-semibold mb-1">Email</h4>
                <a href="mailto:info@padregarcia.gov.ph" class="hover:text-primary-gold transition-colors">info@padregarcia.gov.ph</a>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-[#10174b] text-white py-12">
    <div class="container mx-auto px-6">
      <div class="grid md:grid-cols-4 gap-8 mb-8">
        <div>
          <div class="flex items-center gap-2 mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
            <h4 class="font-bold">FranchTrike</h4>
          </div>
          <p class="text-sm text-gray-400">Tricycle Franchising in Padre Garcia</p>
        </div>

        <div>
          <h5 class="font-semibold mb-4">Quick Links</h5>
          <ul class="space-y-2 text-sm text-gray-400">
            <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
            <li><a href="#about" class="hover:text-white transition-colors">About</a></li>
            <li><a href="#faq" class="hover:text-white transition-colors">FAQ</a></li>
            <li><a href="#contact" class="hover:text-white transition-colors">Contact</a></li>
          </ul>
        </div>

        <div>
          <h5 class="font-semibold mb-4">Legal</h5>
          <ul class="space-y-2 text-sm text-gray-400">
            <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Cookie Policy</a></li>
          </ul>
        </div>

        <div>
          <h5 class="font-semibold mb-4">Follow Us</h5>
          <div class="flex gap-4">
            <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-primary-gold transition-colors">
              <i class="bi bi-facebook"></i>
            </a>
            <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-primary-gold transition-colors">
              <i class="bi bi-twitter"></i>
            </a>
            <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-primary-gold transition-colors">
              <i class="bi bi-instagram"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="border-t border-white/10 pt-8 text-center text-sm text-gray-400">
        <p>&copy; 2025 FranchTrike. All rights reserved.</p>
      </div>
    </div>
  </footer>
<!-- Floating Chatbot Button -->
<button id="chatbot-toggle"
  class="fixed bottom-6 right-6 bg-primary-gold text-primary-navy p-4 rounded-full shadow-xl hover:bg-yellow-400 transition-all z-50 group">
  <i class="bi bi-chat-dots-fill text-2xl group-hover:scale-110 transition-transform"></i>
</button>

<!-- Chatbot Popup -->
<div id="chatbot-box"
  class="fixed bottom-24 right-6 w-96 bg-white rounded-2xl shadow-2xl hidden flex-col z-50">
  <div class="bg-primary-navy text-white p-4 flex justify-between items-center rounded-t-2xl">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center">
        <i class="bi bi-robot text-xl"></i>
      </div>
      <div>
        <h4 class="font-semibold">FranchTrike Bot</h4>
        <p class="text-xs text-gray-300">Online</p>
      </div>
    </div>
    <button id="chatbot-close" class="text-white hover:text-primary-gold transition-colors">
      <i class="bi bi-x-lg"></i>
    </button>
  </div>

  <!-- Messages -->
  <div class="p-4 h-96 overflow-y-auto text-sm space-y-4" id="chatbot-messages"></div>
</div>


<script>
  const chatbotToggle = document.getElementById('chatbot-toggle');
  const chatbotBox = document.getElementById('chatbot-box');
  const chatbotMessages = document.getElementById('chatbot-messages');
  const chatbotClose = document.getElementById('chatbot-close');

  // Helper: Bot Message
  function showBotMessage(msg) {
    const botDiv = document.createElement('div');
    botDiv.className = 'flex items-start gap-3';
    botDiv.innerHTML = `
      <div class="w-8 h-8 bg-primary-navy/10 rounded-full flex items-center justify-center flex-shrink-0">
        <i class="bi bi-robot text-primary-navy"></i>
      </div>
      <div class="bg-gray-100 rounded-2xl rounded-tl-none p-3 max-w-[80%]">${msg}</div>
    `;
    chatbotMessages.appendChild(botDiv);
    chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
  }

  // Show chatbot
  chatbotToggle.addEventListener('click', () => {
    chatbotBox.classList.toggle('hidden');
    if (!chatbotBox.classList.contains('hidden')) {
      chatbotMessages.innerHTML = ""; 
      showBotMessage("👋 Hi! Please choose a category:");
      loadCategories();
    }
  });

  chatbotClose.addEventListener('click', () => {
    chatbotBox.classList.add('hidden');
  });

  // Load categories
  function loadCategories() {
    fetch("{{ url('chatbot/categories') }}")
          .then(res => {
        if (!res.ok) {
          throw new Error(`HTTP error! status: ${res.status}`);
        }
        return res.json();
      })
      .then(categories => {
        console.log('Categories received:', categories); // Debug log
        
        if (!categories || categories.length === 0) {
          showBotMessage("⚠️ No categories found. Please ask the admin to add FAQs.");
          return;
        }
        
        // Clear any existing buttons
        const existingButtons = chatbotMessages.querySelectorAll('button');
        existingButtons.forEach(btn => btn.remove());
        
        categories.forEach(cat => {
          if (!cat || cat.trim() === "") return;
          const btn = document.createElement('button');
          btn.className = "block w-full text-left px-3 py-2 mt-2 bg-primary-gold text-primary-navy rounded-lg hover:bg-yellow-400 transition";
          btn.innerText = cat;
          btn.onclick = () => {
            // Clear previous buttons
            const buttons = chatbotMessages.querySelectorAll('button');
            buttons.forEach(b => b.remove());
            
            showBotMessage(`📂 Category selected: <b>${cat}</b><br>Now pick a question:`);
            loadQuestions(cat);
          };
          chatbotMessages.appendChild(btn);
        });
        
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
      })
      .catch(error => {
        console.error('Error loading categories:', error); // Debug log
        showBotMessage("❌ Failed to load categories. Please try again later.");
      });
  }

  // Load questions
  function loadQuestions(category) {
    fetch("{{ url('chatbot/questions') }}/" + encodeURIComponent(category))
          .then(res => {
        if (!res.ok) {
          throw new Error(`HTTP error! status: ${res.status}`);
        }
        return res.json();
      })
      .then(questions => {
        console.log('Questions received:', questions); // Debug log
        
        if (!questions || questions.length === 0) {
          showBotMessage("⚠️ No questions available for this category.");
          addBackToCategories();
          return;
        }
        
        questions.forEach(q => {
          const btn = document.createElement('button');
          btn.className = "block w-full text-left px-3 py-2 mt-2 bg-blue-100 text-primary-navy rounded-lg hover:bg-blue-200 transition";
          btn.innerText = q.question;
          btn.onclick = () => {
            // Clear buttons and show user message
            const buttons = chatbotMessages.querySelectorAll('button');
            buttons.forEach(b => b.remove());
            
            showUserMessage(q.question);
            loadAnswer(q.id, q.question);
          };
          chatbotMessages.appendChild(btn);
        });
        
        addBackToCategories();
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
      })
      .catch(error => {
        console.error('Error loading questions:', error); // Debug log
        showBotMessage("❌ Failed to load questions. Please try again later.");
        addBackToCategories();
      });
  }

  // Show user message
  function showUserMessage(message) {
    const userDiv = document.createElement('div');
    userDiv.className = 'flex items-start gap-3 justify-end';
    userDiv.innerHTML = `
      <div class="bg-primary-navy text-white rounded-2xl rounded-tr-none p-3 max-w-[80%]">${message}</div>
      <div class="w-8 h-8 bg-primary-navy rounded-full flex items-center justify-center flex-shrink-0">
        <i class="bi bi-person-fill text-white"></i>
      </div>
    `;
    chatbotMessages.appendChild(userDiv);
    chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
  }

  // Load answer
  function loadAnswer(id, questionText) {
    fetch("{{ url('chatbot/answer') }}/" + id)
      .then(res => {
        if (!res.ok) {
          throw new Error(`HTTP error! status: ${res.status}`);
        }
        return res.json();
      })
      .then(data => {
        console.log('Answer received:', data); // Debug log
        showBotMessage(data.answer ?? "❓ Sorry, no answer found for this question.");
        addBackToCategories();
      })
      .catch(error => {
        console.error('Error loading answer:', error); // Debug log
        showBotMessage("❌ Failed to load answer. Please try again later.");
        addBackToCategories();
      });
  }

  // Add back to categories button
  function addBackToCategories() {
    const backBtn = document.createElement('button');
    backBtn.className = "block w-full text-center px-3 py-2 mt-4 bg-gray-200 text-primary-navy rounded-lg hover:bg-gray-300 transition";
    backBtn.innerText = "← Back to Categories";
    backBtn.onclick = () => {
      chatbotMessages.innerHTML = "";
      showBotMessage("👋 Please choose a category:");
      loadCategories();
    };
    chatbotMessages.appendChild(backBtn);
    chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
  }
</script>