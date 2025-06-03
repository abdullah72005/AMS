<!-- Improved compatibility of back to top link: See: https://github.com/othneildrew/Best-README-Template/pull/73 -->

<a id="readme-top"></a>

<!--
*** Thanks for checking out the AMS (Alumni Management Software). If you have a suggestion that would make this better, please fork the repo and create a pull request or open an issue with the tag "enhancement".
*** Don't forget to give the project a star!
*** Now go create something AMAZING! :)
-->

<!-- PROJECT SHIELDS -->

<br />
<div align="center">


  <h2 align="center">Alumni Management Software (AMS)</h2>

  <p align="center">
    A centralized platform for universities and colleges to manage and engage alumni through networking, events, mentorship, and donations.
    <br />
    <a href="#about-the-project"><strong>Explore the Docs »</strong></a>
    <br />
    <br />
  </p>
</div>

<!-- TABLE OF CONTENTS -->

<details>
  <summary>Table of Contents</summary>
  <ol>
    <li><a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li><a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#roadmap">Roadmap</a></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
    <li><a href="#acknowledgments">Acknowledgments</a></li>
  </ol>
</details>

<!-- ABOUT THE PROJECT -->

## About The Project

The Alumni Management Software (AMS) provides universities and colleges with a secure, centralized platform to engage alumni throughout their lifetime. AMS automates and streamlines:

* Alumni registration and profile management
* Networking and advanced search/filter capabilities
* Event scheduling, RSVPs, and participant tracking
* Mentorship program enrollment and matching
* Donation and crowdfunding management with transaction history
* Newsletter drafting, publishing, and notifications

AMS transforms alumni into active contributors—brand ambassadors, donors, and mentors—while giving administrators and faculty/staff the tools to maintain strong alumni relationships.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

### Built With

* **Frontend:** HTML, CSS, JavaScript, Bootstrap
* **Backend:** PHP 
* **Database:** MySQL, PHP PDO

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- GETTING STARTED -->

## Getting Started

Follow these instructions to set up AMS on your local machine.

### Prerequisites

* PHP 8+ installed
* MySQL 5.7+ 
* Web server (Apache or Nginx)
* Composer (optional, if dependencies are managed)

### Installation

1. **Clone the repository**

   ```sh
   git clone https://github.com/your_org/AMS.git
   cd AMS
   ```
2. **Create a MySQL database** (e.g., `ams_db`) and import the schema:

   ```sql
   CREATE DATABASE ams_db;
   USE ams_db;
   SOURCE database/schema.sql;
   ```
3. **Create a `.env` file** at the project root containing:

   ```env
   host=localhost
   dbname=ams_db
   username=root
   password=your_password
   ```
4. **Set document root** of your web server to the `public/` directory.
5. **Start the application** by visiting `http://localhost` (or your configured domain).

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- USAGE -->

## Usage

1. **Registration & Login**

   * Users register with username, password, and role (Alumni, Student, Faculty/Staff, Admin).
   * Newly registered alumni must be verified by Faculty/Staff before accessing the system.
   * Login redirects users to role-based dashboards.

2. **Alumni Directory**

   * Search and filter alumni by name, graduation year, department, or location.
   * View and edit personal profiles (Alumni, Students).

3. **Event Management**

   * Faculty/Staff schedule, edit, or cancel events with details (name, date, description).
   * Alumni view upcoming events and RSVP.
   * System sends notifications for new or updated events.
   * Faculty/Staff view event participants.

4. **Mentorship Program**

   * Alumni opt in as mentors with fields of expertise.
   * Students browse and filter mentors.
   * Students request mentorship; mentors accept or decline.

5. **Crowdfunding & Donations**

   * Alumni donate to causes using internal payment processing (future PayPal/Stripe integration).
   * View personal donation history.
   * Faculty/Staff view overall donation reports (optional reporting).

6. **Newsletters & Communication**

   * Faculty/Staff draft, edit, and publish newsletters targeting alumni segments.
   * Users receive notifications when new newsletters are published.

7. **Admin Panel**

   * Admins manage all user accounts: view, add, edit, or remove Admin, Faculty/Staff, Students, and Alumni.
   * Assign roles and reset credentials.

*For more details, refer to the project documentation and API endpoints.*

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- ROADMAP -->




<!-- LICENSE -->

## License

Distributed under the MIT License. See `LICENSE.txt` for details.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

