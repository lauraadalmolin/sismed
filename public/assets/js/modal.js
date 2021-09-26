$(function(){
        
    $('input[id$="cnpj"]').inputmask(
        "99.999.999/9999-99"
    );

    $('input[id$="cpf"]').inputmask(
        "999.999.999-99"
    );

    $('input[id$="phone"]').inputmask(
        "(99) 99999-9999"
    ); 
});

function editDoctor(element, action){

    $.post(action, {queryString: ""+element.id+""}, function(data){

        if(JSON.parse(data)) {
            let doctor = JSON.parse(data);
            console.log(doctor)
            $('input[id$="doctorID-edit"]').val(doctor.id);
            $('input[id$="crm-edit"]').val(doctor.crm);
            $('input[id$="name-edit"]').val(doctor.name);
            $('input[id$="email-edit"]').val(doctor.email);
            $('input[id$="phone-edit"]').val(doctor.phone);
            $('input[id$="address-edit"]').val(doctor.address);
            $('input[id$="specialty-edit"]').val(doctor.specialty);
            
        } 
    })
}

function editLaboratory(element, action){

    $.post(action, {queryString: ""+element.id+""}, function(data){

        if(JSON.parse(data)) {
            let lab = JSON.parse(data);

            $('input[id$="labID-edit"]').val(lab.id);
            $('input[id$="cnpj-edit"]').val(lab.cnpj);
            $('input[id$="name-edit"]').val(lab.name);
            $('input[id$="email-edit"]').val(lab.email);
            $('input[id$="phone-edit"]').val(lab.phone);
            $('input[id$="address-edit"]').val(lab.address);
            
        } 
    })
}

function editPatient(element, action){

    $.post(action, {queryString: ""+element.id+""}, function(data){

        if(JSON.parse(data)) {
            let paciente = JSON.parse(data);

            $('input[id$="patientID-edit"]').val(paciente.id);
            $('input[id$="cpf-edit"]').val(paciente.cpf);
            $('input[id$="name-edit"]').val(paciente.name);
            $('input[id$="email-edit"]').val(paciente.email);
            $('input[id$="phone-edit"]').val(paciente.phone);
            $('input[id$="address-edit"]').val(paciente.address);
            $('input[id$="age-edit"]').val(paciente.age);
            $('input[id$="gender-edit"]').val(paciente.gender);
            
        } 
    })
}

function editConsulta(element, action){

    $.post(action, {queryString: ""+element.id+""}, function(data){

        if(JSON.parse(data)) {
            let consulta = JSON.parse(data);
            let date = consulta.date.split(" ");
            $('input[id$="consultaID-edit"]').val(consulta.id);
            $('input[id$="patientID-edit"]').val(consulta.patientID);
            $('input[id$="patient"]').val(consulta.patient);
            $('input[id$="prescription-edit"]').val(consulta.prescription);
            $('textarea[id$="observations-edit"]').val(consulta.observations);
            $('input[id$="date-edit"]').val(date[0]);
            $('input[id$="timepicker-edit"]').val(date[1]);
            
        } 
    })
}

function editExam(element, action){

    $.post(action, {queryString: ""+element.id+""}, function(data){
        if(JSON.parse(data)) {
            let exame = JSON.parse(data);
            let date = exame.date.split(" ");
            $('input[id$="examID"]').val(exame.id);
            $('input[id$="patientID-edit"]').val(exame.patientID);
            $('input[id$="patient"]').val(exame.patient);
            $('input[id$="type-edit"]').val(exame.type);
            $('input[id$="result-edit"]').val(exame.result);
            $('input[id$="date-edit"]').val(date[0]);
            $('input[id$="timepicker-edit"]').val(date[1]);
            
        } 
    })
}