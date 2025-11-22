const homeSection = document.getElementById("homeSection");
const attendanceSection = document.getElementById("attendanceSection");
const addStudentSection = document.getElementById("addStudentSection");

document.getElementById("homeLink").addEventListener("click", e => { e.preventDefault(); show(homeSection); });
document.getElementById("attendanceLink").addEventListener("click", e => { e.preventDefault(); show(attendanceSection); });
document.getElementById("addStudentLink").addEventListener("click", e => { e.preventDefault(); show(addStudentSection); });

function show(section){
  [homeSection, attendanceSection, addStudentSection].forEach(s => s.classList.add("hidden"));
  section.classList.remove("hidden");
}

show(attendanceSection);


// TABLE
const attendanceBody = document.getElementById("attendanceBody");

function updateRow(row){
  const pBoxes = row.querySelectorAll("input.p");
  const paBoxes = row.querySelectorAll("input.pa");

  const pList = pBoxes.length ? pBoxes : Array.from(row.querySelectorAll("input")).filter(i=>i.classList.contains("p") || i.previousSibling?.textContent?.trim()==='P' || i.parentElement?.textContent?.includes('P'));
  const paList = paBoxes.length ? paBoxes : Array.from(row.querySelectorAll("input")).filter(i=>i.classList.contains("pa") || i.previousSibling?.textContent?.trim()==='Pa' || i.parentElement?.textContent?.includes('Pa'));

  const pCheckedCount = Array.from(row.querySelectorAll("input.p")).filter(i=>i.checked).length;
  const pTotal = 6;
  const absences = pTotal - pCheckedCount;

  const parCount = Array.from(row.querySelectorAll("input.pa")).filter(i=>i.checked).length;

  row.querySelector(".abs").textContent = absences + " Abs";
  row.querySelector(".par").textContent = parCount + " Par";

  row.classList.remove("green-row","yellow-row","red-row");
  let msg = "";
  if(absences < 3){
    row.classList.add("green-row");
    msg = "Good attendance – Excellent participation";
  } else if(absences <= 4){
    row.classList.add("yellow-row");
    msg = "Warning – attendance low – You need to participate more";
  } else {
    row.classList.add("red-row");
    msg = "Excluded – too many absences – You need to participate more";
  }
  row.querySelector(".msg").textContent = msg;
}

function attachRowListeners(row){
  row.querySelectorAll("input[type='checkbox']").forEach(ch => {
    ch.addEventListener("change", () => updateRow(row));
  });
}

function initExistingRows(){
  attendanceBody.querySelectorAll("tr").forEach(row=>{

    const inputs = row.querySelectorAll("input[type='checkbox']");

    if(inputs.length === 12){
      inputs.forEach((inp, i) => {
        inp.classList.remove("p","pa");
        if(i % 2 === 0) inp.classList.add("p"); 
        else inp.classList.add("pa"); 
      });
    }
    attachRowListeners(row);
    updateRow(row);
  });
}

initExistingRows();


// FORM 
const form = document.getElementById("studentForm");
const idInput = document.getElementById("studentId");
const lastInput = document.getElementById("lastName");
const firstInput = document.getElementById("firstName");
const emailInput = document.getElementById("email");

const idError = document.getElementById("idError");
const lastError = document.getElementById("lastNameError");
const firstError = document.getElementById("firstNameError");
const emailError = document.getElementById("emailError");

function clearErrors(){
  idError.textContent = "";
  lastError.textContent = "";
  firstError.textContent = "";
  emailError.textContent = "";
}

function validateForm(){
  clearErrors();
  let ok = true;
  const idVal = idInput.value.trim();
  const lastVal = lastInput.value.trim();
  const firstVal = firstInput.value.trim();
  const emailVal = emailInput.value.trim();

  if(!/^[0-9]+$/.test(idVal)){
    idError.textContent = "Student ID must contain only numbers.";
    ok = false;
  }
  if(!/^[A-Za-zÀ-ÖØ-öø-ÿ '-]+$/.test(lastVal) || lastVal.length===0){
    lastError.textContent = "Last name must contain only letters.";
    ok = false;
  }
  if(!/^[A-Za-zÀ-ÖØ-öø-ÿ '-]+$/.test(firstVal) || firstVal.length===0){
    firstError.textContent = "First name must contain only letters.";
    ok = false;
  }
  if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)){
    emailError.textContent = "Enter a valid email address.";
    ok = false;
  }
  return ok;
}

function createStudentRow(lastName, firstName, studentId, email){
  const tr = document.createElement("tr");

  const tdLast = document.createElement("td"); tdLast.className = "last"; tdLast.textContent = lastName;
  const tdFirst = document.createElement("td"); tdFirst.className = "first"; tdFirst.textContent = firstName;
  tr.appendChild(tdLast);
  tr.appendChild(tdFirst);

  for(let s=0; s<6; s++){
    const tdP = document.createElement("td");
    const pbox = document.createElement("input"); pbox.type = "checkbox"; pbox.className = "p";
    tdP.appendChild(pbox);
    tr.appendChild(tdP);

    const tdPa = document.createElement("td");
    const pabox = document.createElement("input"); pabox.type = "checkbox"; pabox.className = "pa";
    tdPa.appendChild(pabox);
    tr.appendChild(tdPa);
  }

  const tdAbs = document.createElement("td"); tdAbs.className = "abs"; tdAbs.textContent = "0 Abs";
  const tdPar = document.createElement("td"); tdPar.className = "par"; tdPar.textContent = "0 Par";
  const tdMsg = document.createElement("td"); tdMsg.className = "msg"; tdMsg.textContent = "";

  tr.dataset.studentId = studentId;
  tr.dataset.email = email;

  tr.appendChild(tdAbs);
  tr.appendChild(tdPar);
  tr.appendChild(tdMsg);

  return tr;
}

form.addEventListener("submit", function(e){
  e.preventDefault();
  if(!validateForm()) return;

  const idVal = idInput.value.trim();
  const lastVal = lastInput.value.trim();
  const firstVal = firstInput.value.trim();
  const emailVal = emailInput.value.trim();

  const newRow = createStudentRow(lastVal, firstVal, idVal, emailVal);
  attendanceBody.appendChild(newRow);
  attachRowListeners(newRow);
  updateRow(newRow);

  form.reset();
  clearErrors();

  show(attendanceSection);
  newRow.scrollIntoView({behavior:"smooth", block:"center"});
});

[idInput, lastInput, firstInput, emailInput].forEach(inp=>{
  inp.addEventListener("input", () => {
    if(inp === idInput) idError.textContent = "";
    if(inp === lastInput) lastError.textContent = "";
    if(inp === firstInput) firstError.textContent = "";
    if(inp === emailInput) emailError.textContent = "";
  });
});


