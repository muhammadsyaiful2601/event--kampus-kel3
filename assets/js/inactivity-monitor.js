(function() {
    'use strict';

    const SESSION_TIMEOUT = 600; // 10 minutes in seconds
    const WARNING_BEFORE_LOGOUT = 120; // Show warning 2 minutes before logout
    const PING_INTERVAL = 60000; // Ping server every 60 seconds to keep session alive

    let inactivityTimer;
    let warningTimer;
    let warningModal;
    let countdownDisplay;
    let countdownInterval;
    let pingInterval;

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        createWarningModal();
        startInactivityMonitor();
    });

    // Create warning modal
    function createWarningModal() {
        warningModal = document.createElement('div');
        warningModal.id = 'inactivityWarningModal';
        warningModal.className = 'modal fade';
        warningModal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title">Peringatan Session</h5>
                    </div>
                    <div class="modal-body">
                        <p>Anda akan logout secara otomatis dalam <strong id="countdown">${WARNING_BEFORE_LOGOUT}</strong> detik karena tidak ada aktivitas.</p>
                        <p class="mb-0 text-muted">Apakah Anda masih aktif?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='/logout'">Logout</button>
                        <button type="button" class="btn btn-primary" id="stayLoggedInBtn">Tetap Login</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(warningModal);

        // Stay logged in button handler
        document.getElementById('stayLoggedInBtn').addEventListener('click', function() {
            resetInactivityTimer();
            hideWarningModal();
        });

        countdownDisplay = document.getElementById('countdown');
    }

    // Start inactivity monitoring
    function startInactivityMonitor() {
        // Reset timer on user activity
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart'];
        events.forEach(event => {
            document.addEventListener(event, resetInactivityTimer);
        });

        // Start ping interval to keep session alive
        pingInterval = setInterval(sendKeepAlivePing, PING_INTERVAL);

        // Initial timer start
        resetInactivityTimer();
    }

    // Reset inactivity timer
    function resetInactivityTimer() {
        clearTimeout(inactivityTimer);
        clearTimeout(warningTimer);
        clearInterval(countdownInterval);
        hideWarningModal();

        // Set timer to show warning before logout
        warningTimer = setTimeout(showWarningModal, (SESSION_TIMEOUT - WARNING_BEFORE_LOGOUT) * 1000);
    }

    // Show warning modal with countdown
    function showWarningModal() {
        warningModal.classList.add('show');
        let countdown = WARNING_BEFORE_LOGOUT;

        countdownInterval = setInterval(function() {
            countdown--;
            if (countdownDisplay) {
                countdownDisplay.textContent = countdown;
            }

            if (countdown <= 0) {
                clearInterval(countdownInterval);
                performLogout();
            }
        }, 1000);
    }

    // Hide warning modal
    function hideWarningModal() {
        if (warningModal) {
            warningModal.classList.remove('show');
        }
    }

    // Perform logout
    function performLogout() {
        clearInterval(pingInterval);
        clearInterval(countdownInterval);
        hideWarningModal();
        window.location.href = '/logout';
    }

    // Send keep-alive ping to server
    function sendKeepAlivePing() {
        fetch('/api/keep-alive', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        }).catch(function(error) {
            console.log('Keep-alive ping failed:', error);
        });
    }
})();