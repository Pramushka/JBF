document.addEventListener("DOMContentLoaded", function() {
    showStep(1);
});

let selectedSkills = [];
let selectedJobPositions = [];
let selectedJobIndustries = [];

function showStep(step) {
    const steps = document.querySelectorAll('.form-step');
    steps.forEach(function(stepElement, index) {
        stepElement.classList.remove('active');
        if (index === step - 1) {
            stepElement.classList.add('active');
        }
    });
}

function nextStep(currentStep) {
    showStep(currentStep + 1);
}

function addSkill() {
    const skillInput = document.getElementById('skillInput');
    const skill = skillInput.value.trim();
    
    if (skill.length < 1 || selectedSkills.includes(skill) || selectedSkills.length >= 5) {
        return;
    }

    selectedSkills.push(skill);
    updateSkillsDisplay();
    skillInput.value = '';
}

function addJobPosition() {
    const jobPositionSelect = document.getElementById('jobPositionSelect');
    const jobPosition = jobPositionSelect.value;
    
    if (jobPosition.length < 1 || selectedJobPositions.includes(jobPosition)) {
        return;
    }

    selectedJobPositions.push(jobPosition);
    updateJobPositionsDisplay();
    jobPositionSelect.value = '';
}

function addJobIndustry() {
    const jobIndustrySelect = document.getElementById('jobIndustrySelect');
    const jobIndustry = jobIndustrySelect.value;
    
    if (jobIndustry.length < 1 || selectedJobIndustries.includes(jobIndustry)) {
        return;
    }

    selectedJobIndustries.push(jobIndustry);
    updateJobIndustriesDisplay();
    jobIndustrySelect.value = '';
}

function removeSkill(skill) {
    selectedSkills = selectedSkills.filter(s => s !== skill);
    updateSkillsDisplay();
}

function removeJobPosition(jobPosition) {
    selectedJobPositions = selectedJobPositions.filter(j => j !== jobPosition);
    updateJobPositionsDisplay();
}

function removeJobIndustry(jobIndustry) {
    selectedJobIndustries = selectedJobIndustries.filter(j => j !== jobIndustry);
    updateJobIndustriesDisplay();
}

function updateSkillsDisplay() {
    const skillsContainer = document.getElementById('selectedSkills');
    skillsContainer.innerHTML = '';
    selectedSkills.forEach(skill => {
        const div = document.createElement('div');
        div.classList.add('skill');
        div.innerHTML = `${skill} <i class="fa fa-times" onclick="removeSkill('${skill}')"></i>`;
        skillsContainer.appendChild(div);
    });
    document.getElementById('skillsInput').value = selectedSkills.join(',');
}

function updateJobPositionsDisplay() {
    const jobPositionsContainer = document.getElementById('selectedJobPositions');
    jobPositionsContainer.innerHTML = '';
    selectedJobPositions.forEach(jobPosition => {
        const div = document.createElement('div');
        div.classList.add('job-position');
        div.innerHTML = `${jobPosition} <i class="fa fa-times" onclick="removeJobPosition('${jobPosition}')"></i>`;
        jobPositionsContainer.appendChild(div);
    });
    document.getElementById('jobPositionsInput').value = selectedJobPositions.join(',');
}

function updateJobIndustriesDisplay() {
    const jobIndustriesContainer = document.getElementById('selectedJobIndustries');
    jobIndustriesContainer.innerHTML = '';
    selectedJobIndustries.forEach(jobIndustry => {
        const div = document.createElement('div');
        div.classList.add('job-industry');
        div.innerHTML = `${jobIndustry} <i class="fa fa-times" onclick="removeJobIndustry('${jobIndustry}')"></i>`;
        jobIndustriesContainer.appendChild(div);
    });
    document.getElementById('jobIndustriesInput').value = selectedJobIndustries.join(',');
}