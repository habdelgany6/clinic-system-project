document.addEventListener("DOMContentLoaded", () => {
    loadAppointments();
    loadPatients();
    loadDoctors();
});

function loadPatients() {
    fetch("get_patients.php")
        .then((res) => res.json())
        .then((patients) => {
            const patientSelect = document.getElementById("patientSelect");
            patients.forEach((patient) => {
                const option = document.createElement("option");
                option.value = patient.patient_id;
                option.textContent = patient.name;
                patientSelect.appendChild(option);
            });
        })
        .catch((err) => console.error("Error loading patients:", err));
}

function loadDoctors() {
    fetch("get_doctors.php")
        .then((res) => res.json())
        .then((doctors) => {
            const doctorSelect = document.getElementById("doctorSelect");
            doctors.forEach((doctor) => {
                const option = document.createElement("option");
                option.value = doctor.doctor_id;
                option.textContent = doctor.name;
                doctorSelect.appendChild(option);
            });
        })
        .catch((err) => console.error("Error loading doctors:", err));
}

function bookAppointment() {
    const patient_id = document.getElementById("patientSelect").value;
    const doctor_id = document.getElementById("doctorSelect").value;
    const date = document.getElementById("date").value;

    if (!patient_id || !doctor_id || !date) {
        alert("Please fill in all fields.");
        return;
    }

    fetch("insert_appointment.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ patient_id, doctor_id, date }),
        })
        .then((res) => res.json())
        .then((data) => {
            alert(data.message || data.success || "Appointment booked.");
            loadAppointments();
            document.getElementById("patientSelect").value = "";
            document.getElementById("doctorSelect").value = "";
            document.getElementById("date").value = "";
        })
        .catch((err) => console.error("Error booking appointment:", err));
}

function loadAppointments() {
    fetch("get_appointments.php")
        .then((res) => res.json())
        .then((appointments) => {
            const table = document.getElementById("apptTable");
            table.innerHTML = "";

            if (appointments.length === 0) {
                table.innerHTML =
                    `<tr><td colspan="5">No appointments found.</td></tr>`;
                return;
            }

            appointments.forEach((app) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${app.patient_name}</td>
                    <td>${app.doctor_name}</td>
                    <td>${new Date(app.appointment_date).toLocaleString()}</td>
                    <td>${app.status}</td>
                    <td>
                        <a href="edit.php?id=${app.id}" class="btn-edit">Edit</a>
                        <a href="delete.php?id=${app.id}" class="btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                `;
                table.appendChild(row);
            });
        })
        .catch((err) => console.error("Error loading appointments:", err));
}