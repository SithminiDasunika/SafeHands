function goToStep(stepNumber) {
    // Hide all step sections
    var sections = document.querySelectorAll('section');
    for (var i = 0; i < sections.length; i++) {
        sections[i].classList.add('hidden');
    }
    
    // Show target step section
    var targetSection = document.getElementById('step-' + stepNumber + '-content');
    if (targetSection) {
        targetSection.classList.remove('hidden');
    }
    
    // Update Progress Tracker
    updateTracker(stepNumber);
    
    // Scroll to top smoothly
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateTracker(currentStep) {
    var steps = [1, 2, 3];
    for (var i = 0; i < steps.length; i++) {
        var num = steps[i];
        var stepItem = document.getElementById('step-item-' + num);
        var label = document.getElementById('label-' + num);
        
        if (!stepItem || !label) continue;

        stepItem.classList.remove('active', 'completed');

        if (num < currentStep) {
            // Completed state
            stepItem.classList.add('completed');
            label.innerHTML = '&#10003;';
        } else if (num === currentStep) {
            // Active state
            stepItem.classList.add('active');
            label.innerHTML = num;
        } else {
            // Future state
            label.innerHTML = num;
        }
    }
}

function submitForm() {
    // Hide all sections and progress bar
    var sections = document.querySelectorAll('section');
    for (var i = 0; i < sections.length; i++) {
        sections[i].classList.add('hidden');
    }
    
    var tracker = document.getElementById('progress-tracker');
    var loadingState = document.getElementById('loading-state');
    var successState = document.getElementById('success-state');

    if (tracker) tracker.classList.add('hidden');
    if (loadingState) loadingState.classList.remove('hidden');

    // Simulate API Request delay
    setTimeout(function() {
        if (loadingState) loadingState.classList.add('hidden');
        if (successState) successState.classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }, 2000);
}