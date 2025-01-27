# FAiQ

<p align="center">
  <img src="path/to/logo.png" alt="FAiQ Logo" width="200"/>
</p>

FAiQ transforms customer support through AI-powered document intelligence. Our platform automatically converts your documentation into smart, searchable FAQ pages that provide instant, accurate answers to user questions. Whether you're managing technical documentation, knowledge bases, or support content, FAiQ's advanced AI technology ensures your users get the right answers 24/7.


## ✨ Key Features

- **Intelligent FAQ Generation**
  - Automatic FAQ creation from your documentation
  - Smart content organization

- **AI-Powered Support**
  - Natural language understanding
  - Context-aware responses
  - 24/7 automated customer support

- **Team Management**
  - Content management dashboard
  - Analytics and insights
  - Role-based access control

- **Enterprise Integration**
  - Custom branding options
  - API integration
  - Usage analytics and reporting

## 💡 Use Cases

- **Customer Support**: Create an AI-powered knowledge base that answers customer questions instantly
- **Documentation**: Transform technical documentation into interactive FAQ pages
- **Internal Knowledge**: Build searchable company wikis and support portals
- **Product Support**: Provide automated product support and troubleshooting

## 🚀 Quick Start

### Prerequisites

- PHP 8.x
- Composer
- Node.js (16+)
- Docker
- Redis

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-org/faiq.git
   cd faiq
   ```

2. **Set up environment**
   ```bash
   cp .env.example .env
   composer install
   php artisan key:generate
   ```

3. **Start Docker environment**
   ```bash
   sail up --build -d
   sail npm install
   ```

4. **Initialize the application**
   ```bash
   sail artisan migrate
   sail artisan storage:link
   ```

### Starting Development Environment

```bash
# Start application servers
sail up -d && sail npm run dev

# Start WebSocket server (new terminal)
sail artisan reverb:start --debug

# Start queue workers (separate terminals)
sail artisan queue:listen --queue=batch --timeout=0
sail artisan queue:listen --queue=ask --timeout=0
sail artisan queue:listen --timeout=0
```

## 🔄 Core Workflows

### Content Processing Pipeline

1. **Document Import**
   - Upload support documentation
   - Import existing FAQs
   - Connect to knowledge bases

2. **AI Processing**
   ```php
   // Process documentation
   $document = new Document($file);
   $document->process();
   
   // Generate FAQ embeddings
   $embeddings = $document->generateEmbeddings();
   ```

3. **FAQ Generation**
   ```php
   // Generate FAQ pages
   (new JobService())->batchPublish(Team::find($teamId));
   
   // Update FAQ content
   (new JobService())->batchRetrieve(Team::find($teamId));
   ```

### Support Flow

1. User asks question on FAQ page
2. System finds relevant documentation
3. AI generates contextual response
4. Answer is displayed instantly to user

## 🏗 Project Structure

```
faiq/
├── app/                 # Core application code
│   ├── Http/           # Controllers and Middleware
│   ├── Models/         # Eloquent models
│   └── Services/       # Business logic services
├── config/             # Configuration files
├── database/           # Migrations and seeders
├── resources/          # Frontend assets
├── routes/             # API and web routes
└── tests/              # Automated tests
```

## 🛠 Configuration

Key configuration options in `.env`:

```env
AI_MODEL=gpt-4
EMBEDDING_MODEL=text-embedding-3-small
FAQ_UPDATE_INTERVAL=3600
MAX_TOKENS_PER_RESPONSE=8000
```

## 📊 Analytics

Track key metrics including:
- Question response rates
- User satisfaction scores
- Popular topics
- Support coverage

## 📚 Documentation

- [Getting Started Guide](docs/getting_started_guide.mdx)
- [Advanced Customization](docs/advanced_topics_and_customization.mdx)
- [API Reference](docs/api_reference.md)

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the [MIT License](LICENSE).

## 🆘 Support

- Documentation: [docs.faiq.ai](https://docs.faiq.ai)
- Issues: [GitHub Issues](https://github.com/your-org/faiq/issues)
- Email: support@faiq.ai

## 🔐 Security

Please report security vulnerabilities to security@faiq.ai

---

<p align="center">
  Made with ❤️ by the FAiQ Team
</p>
