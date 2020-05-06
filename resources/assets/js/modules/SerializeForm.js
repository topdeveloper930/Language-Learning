export default class SerializeForm {
    toJson (formOrId) {
        const form = formOrId.nodeName === 'FORM' ? formOrId : document.forms[formOrId],
            inputs = form.elements,
            formData = {};

        for (let i = 0; i < inputs.length; i++) {
            if( inputs[i].name !== '' )
                formData[inputs[i].name] = inputs[i].value;
        }

        return formData;
    }
}

window.SerializeForm = new SerializeForm;