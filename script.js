// Resume JavaScript functionality

document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add animation to project cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Initially hide projects and observe them
    const projects = document.querySelectorAll('.project');
    projects.forEach(project => {
        project.style.opacity = '0';
        project.style.transform = 'translateY(20px)';
        project.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(project);
    });

    // Add click event to project titles to toggle details
    projects.forEach(project => {
        const title = project.querySelector('h3');
        const details = project.querySelector('.project-details');
        const description = project.querySelector('.project-description');

        title.style.cursor = 'pointer';
        title.addEventListener('click', function() {
            const isExpanded = details.style.display !== 'none';

            if (isExpanded) {
                details.style.display = 'none';
                description.style.display = 'none';
                title.style.color = '#333';
            } else {
                details.style.display = 'block';
                description.style.display = 'block';
                title.style.color = '#667eea';
            }
        });

        // Initially hide details
        details.style.display = 'none';
        description.style.display = 'none';
    });

    // Add print functionality
    const printButton = document.createElement('button');
    printButton.textContent = 'Print Resume';
    printButton.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #667eea;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: background-color 0.3s ease;
    `;
    printButton.addEventListener('mouseover', function() {
        this.style.backgroundColor = '#5a6fd8';
    });
    printButton.addEventListener('mouseout', function() {
        this.style.backgroundColor = '#667eea';
    });
    printButton.addEventListener('click', function() {
        window.print();
    });

    document.body.appendChild(printButton);

    // Add skill highlighting on hover
    const skillItems = document.querySelectorAll('.skill-category li');
    skillItems.forEach(item => {
        item.addEventListener('mouseover', function() {
            this.style.backgroundColor = '#667eea';
            this.style.color = 'white';
        });
        item.addEventListener('mouseout', function() {
            this.style.backgroundColor = '#f8f9fa';
            this.style.color = 'black';
        });
    });

    // Add dynamic year calculation
    const currentYear = new Date().getFullYear();
    const yearElements = document.querySelectorAll('.date');
    yearElements.forEach(element => {
        if (element.textContent.includes('Year')) {
            element.textContent = element.textContent.replace('Year', currentYear);
        }
    });

    // Add contact info validation (simple email check)
    const emailElement = document.querySelector('.contact-info span:first-child');
    if (emailElement && emailElement.textContent.includes('@')) {
        emailElement.style.cursor = 'pointer';
        emailElement.addEventListener('click', function() {
            const email = this.textContent.replace('📧 ', '');
            window.location.href = `mailto:${email}`;
        });
    }

    // Add loading animation
    const body = document.body;
    body.style.opacity = '0';
    body.style.transition = 'opacity 0.5s ease';

    setTimeout(() => {
        body.style.opacity = '1';
    }, 100);
});

// Add keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'p' && e.ctrlKey) {
        e.preventDefault();
        window.print();
    }
});

// Add responsive menu toggle (for mobile, though not implemented in HTML)
function toggleMenu() {
    // This would be used if we had a mobile menu
    console.log('Menu toggle clicked');
}
