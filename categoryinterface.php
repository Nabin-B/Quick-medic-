<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Appointment</title>
<style>
    /* Reset & base */
    * { margin:0; padding:0; box-sizing:border-box; font-family:Arial,sans-serif; }
    body { background-color:#f0f4f8; color:#333; display:flex; justify-content:center; padding:50px 20px; }
    h2 { text-align:center; margin-bottom:20px; color:#2563eb; }
    form { background:#fff; padding:30px; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,0.1); width:100%; max-width:600px; }

    label { display:block; margin-top:15px; font-weight:bold; color:#1e40af; }
    select, input[type="date"] { width:100%; padding:10px; margin-top:5px; border:1px solid #cbd5e1; border-radius:8px; font-size:1em; }
    select:focus, input:focus { border-color:#2563eb; outline:none; box-shadow:0 0 5px rgba(37,99,235,0.3); }

    button { margin-top:25px; width:100%; padding:12px; background:#2563eb; color:#fff; font-size:1.1em; font-weight:bold; border:none; border-radius:10px; cursor:pointer; transition:background 0.3s, transform 0.2s; }
    button:hover { background:#1e40af; transform:translateY(-2px); }

    @media(max-width:600px){ form{padding:20px;} }
</style>
</head>
<body>

<form action="Dashboard.php" method="POST">
    <h2>Book Appointment</h2>

    <label>Province:</label>
    <select id="province" name="province" required>
        <option value="">-- Select Province --</option>
        <?php
        $conn = new mysqli("localhost","root","","category_info");
        $res = $conn->query("SELECT * FROM province");
        while($row=$res->fetch_assoc()){
            echo "<option value='{$row['id']}'>{$row['name']}</option>";
        }
        ?>
    </select>

    <label>District:</label>
    <select id="district" name="district" required>
        <option value="">-- Select District --</option>
    </select>

    <label>Hospital:</label>
    <select id="hospital" name="hospital" required>
        <option value="">-- Select Hospital --</option>
    </select>

    <button type="submit">View Details</button>
</form>

<script>
function fetchData(url, targetId, reset=[]){
    fetch(url)
    .then(res=>res.text())
    .then(data=>{
        document.getElementById(targetId).innerHTML = data;
        reset.forEach(r => document.getElementById(r).innerHTML = "<option value=''>-- Select --</option>");
    });
}

// Dropdown chaining
document.getElementById("province").addEventListener("change", ()=>{ fetchData("get_district.php?province_id="+document.getElementById("province").value, "district", ["hospital","medical_field","doctor","appointment_time"]); });
document.getElementById("district").addEventListener("change", ()=>{ fetchData("get_hospital.php?district_id="+document.getElementById("district").value, "hospital", ["medical_field","doctor","appointment_time"]); });
document.getElementById("hospital").addEventListener("change", ()=>{ fetchData("get_medicalfield.php?hospital_id="+document.getElementById("hospital").value, "medical_field", ["doctor","appointment_time"]); });
document.getElementById("medical_field").addEventListener("change", ()=>{ fetchData("get_doctor.php?medical_field_id="+document.getElementById("medical_field").value, "doctor", ["appointment_time"]); });

// Load slots based on doctor + date
function loadAvailableSlots(){
    const doctorId = document.getElementById("doctor").value;
    const date = document.getElementById("calendar").value;
    if(doctorId && date){
        fetch(`get_available_slots.php?doctor_id=${doctorId}&date=${date}`)
        .then(res=>res.text())
        .then(data=>document.getElementById("appointment_time").innerHTML=data);
    } else {
        document.getElementById("appointment_time").innerHTML="<option value=''>-- Select Time --</option>";
    }
}

document.getElementById("doctor").addEventListener("change", loadAvailableSlots);
document.getElementById("calendar").addEventListener("change", loadAvailableSlots);

// Optional: prevent submit if no slot
document.querySelector("form").addEventListener("submit", function(e){
    if(!document.getElementById("appointment_time").value){
        e.preventDefault();
        alert("⚠ Please select a valid time slot.");
    }
});
</script>

</body>
</html>
