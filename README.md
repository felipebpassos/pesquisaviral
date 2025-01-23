<h1 align="center" style="font-weight: bold;">Pesquisa Viral 🌟</h1>

<p align="center">
 <a href="#about">About</a> • 
 <a href="#tech">Tech Stack</a> •  
 <a href="#screenshots">Screenshots</a> • 
 <a href="#demo">Demo</a>
</p>

![Banner](https://github.com/felipebpassos/ViralSearch/blob/main/pv1.png?raw=true)

<p align="center">
    <i>Analyze any business profile on Instagram with ease.</i>
</p>

<p align="center" style="color: gray;">
    <i>👉 Try now <a href="https://pesquisaviral.com">pesquisaviral.com</a></i>
</p>

<h2 id="about">📝 About</h2>

This platform allows you to filter, sort, and visualize performance and activity metrics for any business account on Instagram. With a clean and intuitive interface, users can easily uncover valuable insights into post engagement and activity trends. 

### **Features**

- **Profile Analysis**: Sort and filter posts by number of likes, comments, engagement rate, date, and type of post.
- **Interactive Performance Graphs**: Visualize profile engagement with clean, easy-to-understand, and aesthetically pleasing charts.
- **VIP Beta Access**: A select group of users from a partnered course on Kiwify are part of an exclusive testing phase.
- **Email Whitelisting**: Verify if you're part of the VIP testers through a seamless email verification system.
- **OAuth 2 Login**: Use Facebook to log in effortlessly.

### **Upcoming Features**

- **Freemium, Plus, and Enterprise Plans**: Get more out of your Instagram data with tailored subscription plans (coming soon).
- **Payment Integration**: Simple and secure payment processing for upgrades.

### **Business Vision**

This project is designed as a future SaaS product (B2C), focused on delivering value to Instagram business users looking to optimize their profiles. The flexible freemium model ensures accessibility, while premium plans (Plus and Enterprise) will cater to users who need more advanced features and data insights.

With careful attention to security (OAuth 2.0, JWT, and Redis), performance (asynchronous tasks, caching), and scalability (non-relational databases), this platform is ready to grow alongside its users.

<br>

<h2 id="tech">💻 Tech Stack</h2>

<br>

- **PHP (MVC Architecture)**: Utilizes the **Model-View-Controller (MVC)** pattern, where the business logic (Models), control logic (Controllers), and presentation layer (Views) are well-separated, ensuring maintainability and organization.
  - The application is currently monolithic, with the frontend and backend integrated, meaning the frontend is not decoupled via APIs, but all functionality is managed within the same codebase and server.
  - The architecture was designed for simplicity and efficiency in the early stages, with plans to evolve towards a more modular structure as the application scales.

- **Instagram Graph API**: All data is collected through the Instagram Graph API, ensuring compliance with Instagram's terms and providing reliable, accurate business profile data.
- **Facebook OAuth 2.0**: Easily allows users to log in with their Facebook credentials, streamlining the onboarding process while benefiting the platform by increasing the Meta API usage rate.
- **JWT for Secure Authentication**: Token-based authentication to ensure secure session management and reduce unnecessary requests to Facebook.
- **Redis for Caching and Queuing**:
  - **Token storage**: OAuth tokens are stored with a three-day expiration to optimize API requests.
  - **Asynchronous Searches**: Redis queues allow for fast and scalable search operations.
  - **Recent Searches**: Users can access their last searches without any delay, cached for three days.
- **Non-Relational Database**: Chosen for horizontal scalability and flexibility in handling diverse data structures as the platform grows.
- **Webhook Integration**: Used to collect exclusive VIP user data from Kiwify, enabling seamless whitelisting of test users.

<br>

<h2 id="screenshots">📱 Screenshots</h2>

![Dashboard Preview](https://github.com/felipebpassos/ViralSearch/blob/main/pv2.png?raw=true)  
_Instagram Business Account Analysis._

![Performance Graph](https://github.com/felipebpassos/ViralSearch/blob/main/pv3.png?raw=true)  
_OAuth 2.0 Facebook Login._

![Dashboard](https://github.com/felipebpassos/ViralSearch/blob/main/pv1.png?raw=true)  
_A simple, clean, and minimalistic dashboard._

<br>

<h2 id="demo">🚀 Demo</h2>

[![Demo on YouTube](https://img.shields.io/badge/YouTube-Demo-red?style=for-the-badge&logo=youtube)](https://youtu.be/OjWBcroxG0Y)

<br>

**Let's connect!**  
Feel free to [contact me](mailto:contato@simplifyweb.com.br) for any questions or collaboration opportunities.
