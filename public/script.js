function showSection(sectionID) {
    const allSections = ['home', 'create', 'read', 'update', 'delete'];
    allSections.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });

    const activeSection = document.getElementById(sectionID);
    if (activeSection) {
        activeSection.style.display = 'block';
    }
}

function showToast(toastId, autoHideMs = 3000) {
    const toast = document.getElementById(toastId);
    if (!toast) return;
    toast.classList.remove('toast-hidden');
    toast.style.opacity = '1';
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.classList.add('toast-hidden'), 500);
    }, autoHideMs);
}

// --- Form Handling Logic ---

async function handleFormSubmit(form) {
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn ? submitBtn.innerText : '';
    if (submitBtn) submitBtn.innerText = 'Processing...';

    try {
        const isGet = form.method.toUpperCase() === 'GET';
        let response;
        
        if (isGet) {
            const formData = new FormData(form);
            const params = new URLSearchParams(formData).toString();
            const action = form.getAttribute('action') || 'index.php';
            const url = action + (action.includes('?') ? '&' : '?') + params;
            response = await fetch(url);
        } else {
            response = await fetch(form.action, {
                method: form.method,
                body: new FormData(form)
            });
        }
        
        const finalUrl = response.url;
        const html = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        const urlObj = new URL(finalUrl);
        const status = urlObj.searchParams.get('status');
        
        ['read', 'update', 'delete'].forEach(sectionId => {
            const currentSection = document.getElementById(sectionId);
            const newSectionContent = doc.getElementById(sectionId);
            if (currentSection && newSectionContent) {
                currentSection.innerHTML = newSectionContent.innerHTML;
            }
        });
        
        if (status === 'success') {
            showToast('success-toast');
            form.reset();
        } else if (status === 'updated') {
            showToast('updated-toast');
        } else if (status === 'deleted') {
            showToast('deleted-toast');
        } else if (status === 'notfound' || status === 'invalid') {
            showToast('notfound-toast');
        }

    } catch (err) {
        console.error("AJAX form submission error:", err);
        alert("An error occurred while communicating with the server.");
    } finally {
        if (submitBtn) submitBtn.innerText = originalText;
        // Ensure confirmation flag is cleared if we re-use the same element
        if (form.dataset) delete form.dataset.confirmed;
    }
}

// --- Event Listeners ---

document.addEventListener('submit', (e) => {
    const form = e.target;
    if (form.tagName !== 'FORM') return;

    e.preventDefault();
    handleFormSubmit(form);
});

window.onload = function () {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const section = urlParams.get('section');

    if (section) {
        showSection(section);
    } else if (status === 'success') {
        showSection('create');
    } else if (status === 'updated' || status === 'invalid') {
        showSection('update');
    } else if (status === 'deleted') {
        showSection('delete');
    } else if (status === 'notfound') {
        showSection('update');
    }

    if (status === 'success') {
        showToast('success-toast');
    } else if (status === 'updated') {
        showToast('updated-toast');
    } else if (status === 'deleted') {
        showToast('deleted-toast');
    } else if (status === 'notfound') {
        showToast('notfound-toast');
    }

    if (status || section) {
        window.history.replaceState({}, document.title, window.location.pathname);
    }
};

function toggleMenu() {
    const navMenu = document.getElementById('navMenu');
    navMenu.classList.toggle('active');
}

const originalShowSection = window.showSection;
window.showSection = function(sectionId) {
    if (typeof originalShowSection === 'function') {
        originalShowSection(sectionId);
    }
    const navMenu = document.getElementById('navMenu');
    if (navMenu) {
        navMenu.classList.remove('active');
    }
};
