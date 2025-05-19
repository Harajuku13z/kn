/**
 * Support system JavaScript file
 * Handles AJAX interactions for the support system
 */

document.addEventListener('DOMContentLoaded', function() {
    // Ticket creation form
    const ticketForm = document.getElementById('ticketForm');
    if (ticketForm) {
        ticketForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Disable submit button
            const submitBtn = document.getElementById('submitBtn');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Envoi en cours...';
            
            // Get form data
            const formData = new FormData(ticketForm);
            
            // Send AJAX request
            fetch(ticketForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message and redirect
                    showNotification({
                        title: 'Ticket créé',
                        message: data.message || 'Votre ticket a été créé avec succès.',
                        type: 'success'
                    });
                    
                    // Redirect to the ticket page
                    setTimeout(function() {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    // Show error message
                    if (data.errors) {
                        // Display validation errors
                        Object.keys(data.errors).forEach(key => {
                            const errorElement = document.getElementById(key + '-error');
                            if (errorElement) {
                                errorElement.textContent = data.errors[key][0];
                                errorElement.style.display = 'block';
                                
                                // Add invalid class to input
                                const input = document.getElementById(key);
                                if (input) {
                                    input.classList.add('is-invalid');
                                }
                            }
                        });
                    } else {
                        // Display general error message
                        showNotification({
                            title: 'Erreur',
                            message: data.message || 'Une erreur est survenue lors de la création du ticket.',
                            type: 'danger'
                        });
                    }
                    
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error message
                showNotification({
                    title: 'Erreur',
                    message: 'Une erreur est survenue lors de la création du ticket.',
                    type: 'danger'
                });
                
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        });
    }
    
    // Reply form
    const replyForm = document.getElementById('replyForm');
    if (replyForm) {
        replyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Disable submit button
            const submitBtn = document.getElementById('submitBtn');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Envoi en cours...';
            
            // Get form data
            const formData = new FormData(replyForm);
            
            // Send AJAX request
            fetch(replyForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add the new message to the conversation
                    const messageContainer = document.getElementById('messageContainer');
                    messageContainer.insertAdjacentHTML('beforeend', data.html);
                    
                    // Clear the editor
                    if (window.quill) {
                        window.quill.setText('');
                    }
                    
                    // Scroll to the new message
                    messageContainer.scrollTop = messageContainer.scrollHeight;
                    
                    // Show success message
                    showNotification({
                        title: 'Message envoyé',
                        message: data.message || 'Votre réponse a été envoyée avec succès.',
                        type: 'success'
                    });
                } else {
                    // Show error message
                    if (data.errors) {
                        // Display validation errors
                        Object.keys(data.errors).forEach(key => {
                            const errorElement = document.getElementById(key + '-error');
                            if (errorElement) {
                                errorElement.textContent = data.errors[key][0];
                                errorElement.style.display = 'block';
                            }
                        });
                    } else {
                        // Display general error message
                        showNotification({
                            title: 'Erreur',
                            message: data.message || 'Une erreur est survenue lors de l\'envoi de votre réponse.',
                            type: 'danger'
                        });
                    }
                }
                
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error message
                showNotification({
                    title: 'Erreur',
                    message: 'Une erreur est survenue lors de l\'envoi de votre réponse.',
                    type: 'danger'
                });
                
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        });
    }
    
    // Close ticket button
    const closeTicketBtn = document.querySelector('.close-ticket-btn');
    if (closeTicketBtn) {
        closeTicketBtn.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('closeTicketModal'));
            modal.show();
        });
    }
    
    // Confirm close ticket button
    const confirmCloseTicket = document.getElementById('confirmCloseTicket');
    if (confirmCloseTicket) {
        confirmCloseTicket.addEventListener('click', function() {
            // Disable button
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Fermeture en cours...';
            
            // Get the ticket ID from the URL
            const ticketId = window.location.pathname.split('/').pop();
            
            // Send AJAX request
            fetch(`/support/${ticketId}/close`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close the modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('closeTicketModal'));
                    modal.hide();
                    
                    // Reload the page
                    window.location.reload();
                } else {
                    // Show error message
                    showNotification({
                        title: 'Erreur',
                        message: data.message || 'Une erreur est survenue lors de la fermeture du ticket.',
                        type: 'danger'
                    });
                    
                    // Re-enable button
                    this.disabled = false;
                    this.innerHTML = '<i class="bi bi-check-circle me-2"></i> Fermer le ticket';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error message
                showNotification({
                    title: 'Erreur',
                    message: 'Une erreur est survenue lors de la fermeture du ticket.',
                    type: 'danger'
                });
                
                // Re-enable button
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-check-circle me-2"></i> Fermer le ticket';
            });
        });
    }
    
    // Reopen ticket button
    const reopenTicketBtn = document.querySelector('.reopen-ticket-btn');
    if (reopenTicketBtn) {
        reopenTicketBtn.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('reopenTicketModal'));
            modal.show();
        });
    }
    
    // Confirm reopen ticket button
    const confirmReopenTicket = document.getElementById('confirmReopenTicket');
    if (confirmReopenTicket) {
        confirmReopenTicket.addEventListener('click', function() {
            // Disable button
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Réouverture en cours...';
            
            // Get the ticket ID from the URL
            const ticketId = window.location.pathname.split('/').pop();
            
            // Send AJAX request
            fetch(`/support/${ticketId}/reopen`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close the modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('reopenTicketModal'));
                    modal.hide();
                    
                    // Reload the page
                    window.location.reload();
                } else {
                    // Show error message
                    showNotification({
                        title: 'Erreur',
                        message: data.message || 'Une erreur est survenue lors de la réouverture du ticket.',
                        type: 'danger'
                    });
                    
                    // Re-enable button
                    this.disabled = false;
                    this.innerHTML = '<i class="bi bi-arrow-counterclockwise me-2"></i> Rouvrir le ticket';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error message
                showNotification({
                    title: 'Erreur',
                    message: 'Une erreur est survenue lors de la réouverture du ticket.',
                    type: 'danger'
                });
                
                // Re-enable button
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-arrow-counterclockwise me-2"></i> Rouvrir le ticket';
            });
        });
    }
}); 