/**
 * Admin support system JavaScript file
 * Handles AJAX interactions for the admin support system
 */

document.addEventListener('DOMContentLoaded', function() {
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
                    
                    // Reset template selection
                    const templateSelect = document.getElementById('template_id');
                    if (templateSelect) {
                        templateSelect.value = '';
                    }
                    
                    // Hide template preview
                    const templatePreview = document.getElementById('templatePreview');
                    if (templatePreview) {
                        templatePreview.style.display = 'none';
                        templatePreview.innerHTML = '';
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
    
    // Status actions
    const statusActions = document.querySelectorAll('.status-action');
    if (statusActions.length > 0) {
        statusActions.forEach(action => {
            action.addEventListener('click', function() {
                const status = this.dataset.status;
                
                // Get the ticket ID from the URL
                const ticketId = window.location.pathname.split('/').pop();
                
                // Send AJAX request
                fetch(`/admin/support/${ticketId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update status badges
                        document.querySelectorAll('.ticket-status').forEach(el => {
                            el.className = 'ticket-status status-' + status;
                            el.textContent = data.status_label;
                        });
                        
                        // Update status action buttons
                        statusActions.forEach(btn => {
                            btn.disabled = btn.dataset.status === status;
                        });
                        
                        // Show/hide closed banner
                        const closedBanner = document.querySelector('.ticket-closed-banner');
                        if (closedBanner) {
                            closedBanner.style.display = status === 'closed' ? 'block' : 'none';
                        }
                        
                        // Show success message
                        showNotification({
                            title: 'Statut mis à jour',
                            message: data.message || 'Le statut du ticket a été mis à jour avec succès.',
                            type: 'success'
                        });
                    } else {
                        // Show error message
                        showNotification({
                            title: 'Erreur',
                            message: data.message || 'Une erreur est survenue lors de la mise à jour du statut.',
                            type: 'danger'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Show error message
                    showNotification({
                        title: 'Erreur',
                        message: 'Une erreur est survenue lors de la mise à jour du statut.',
                        type: 'danger'
                    });
                });
            });
        });
    }
    
    // Priority actions
    const priorityActions = document.querySelectorAll('.priority-action');
    if (priorityActions.length > 0) {
        priorityActions.forEach(action => {
            action.addEventListener('click', function() {
                const priority = this.dataset.priority;
                
                // Get the ticket ID from the URL
                const ticketId = window.location.pathname.split('/').pop();
                
                // Send AJAX request
                fetch(`/admin/support/${ticketId}/priority`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ priority: priority })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update priority badges
                        document.querySelectorAll('.priority-badge').forEach(el => {
                            el.className = 'priority-badge priority-' + priority;
                        });
                        
                        // Update priority text
                        const priorityTexts = document.querySelectorAll('.ticket-info-list li:nth-child(3) span:last-child');
                        priorityTexts.forEach(el => {
                            el.textContent = data.priority_label;
                        });
                        
                        // Update priority action buttons
                        priorityActions.forEach(btn => {
                            btn.disabled = btn.dataset.priority === priority;
                        });
                        
                        // Show success message
                        showNotification({
                            title: 'Priorité mise à jour',
                            message: data.message || 'La priorité du ticket a été mise à jour avec succès.',
                            type: 'success'
                        });
                    } else {
                        // Show error message
                        showNotification({
                            title: 'Erreur',
                            message: data.message || 'Une erreur est survenue lors de la mise à jour de la priorité.',
                            type: 'danger'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Show error message
                    showNotification({
                        title: 'Erreur',
                        message: 'Une erreur est survenue lors de la mise à jour de la priorité.',
                        type: 'danger'
                    });
                });
            });
        });
    }
    
    // Template selection
    const templateSelect = document.getElementById('template_id');
    const templatePreview = document.getElementById('templatePreview');
    if (templateSelect && templatePreview) {
        templateSelect.addEventListener('change', function() {
            const templateId = this.value;
            
            if (templateId) {
                // Get template content
                fetch(`/admin/support/templates/${templateId}/get`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show template preview
                        templatePreview.innerHTML = `
                            <h6>Aperçu du modèle</h6>
                            <div>${data.template.content}</div>
                        `;
                        templatePreview.style.display = 'block';
                        
                        // Update editor with template content
                        if (window.quill) {
                            window.quill.root.innerHTML = data.template.content;
                        }
                    } else {
                        // Hide template preview
                        templatePreview.style.display = 'none';
                        templatePreview.innerHTML = '';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Hide template preview
                    templatePreview.style.display = 'none';
                    templatePreview.innerHTML = '';
                });
            } else {
                // Hide template preview
                templatePreview.style.display = 'none';
                templatePreview.innerHTML = '';
                
                // Clear editor
                if (window.quill) {
                    window.quill.setText('');
                }
            }
        });
    }
    
    // Toggle active status for templates
    const toggleSwitches = document.querySelectorAll('.toggle-active');
    if (toggleSwitches.length > 0) {
        toggleSwitches.forEach(toggle => {
            toggle.addEventListener('change', function() {
                const id = this.dataset.id;
                const isActive = this.checked;
                
                fetch(`/admin/support/templates/${id}/toggle`, {
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
                        showNotification({
                            title: 'Statut mis à jour',
                            message: data.message || 'Le statut du modèle a été mis à jour avec succès.',
                            type: 'success'
                        });
                    } else {
                        // Revert toggle state
                        this.checked = !isActive;
                        
                        showNotification({
                            title: 'Erreur',
                            message: data.message || 'Une erreur est survenue lors de la mise à jour du statut.',
                            type: 'danger'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Revert toggle state
                    this.checked = !isActive;
                    
                    showNotification({
                        title: 'Erreur',
                        message: 'Une erreur est survenue lors de la mise à jour du statut.',
                        type: 'danger'
                    });
                });
            });
        });
    }
    
    // Template preview in list
    const previewButtons = document.querySelectorAll('.preview-btn');
    if (previewButtons.length > 0) {
        const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
        const previewContent = document.getElementById('templatePreviewContent');
        
        previewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const content = this.dataset.content;
                previewContent.innerHTML = content;
                previewModal.show();
            });
        });
    }
    
    // Template deletion confirmation
    const deleteButtons = document.querySelectorAll('.delete-btn');
    if (deleteButtons.length > 0) {
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const deleteForm = document.getElementById('deleteForm');
        const templateName = document.getElementById('templateName');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                
                templateName.textContent = name;
                deleteForm.action = `/admin/support/templates/${id}`;
                
                deleteModal.show();
            });
        });
    }
}); 