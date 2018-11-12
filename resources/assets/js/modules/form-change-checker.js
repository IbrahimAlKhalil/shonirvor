import md5 from "blueimp-md5";

export class FormChangeChecker {
    constructor(form, preventSubmission) {
        this.form = form;
        this.md5 = this.getMD5(form);

        preventSubmission && form.addEventListener('submit', evt => {
            if (!this.changed()) {
                evt.preventDefault();
                return false;
            }

            evt.target.submit();
        });
    }

    changed() {
        return this.md5 !== this.getMD5(this.form);
    }

    getMD5() {
        let data = {};

        [...this.form.elements].forEach(element => {
            if (element.type === 'file' && element.files[0]) {
                data[element.name] = element.files[0].lastModified;
                return;
            }

            data[element.name] = element.value;
        });
        return md5(JSON.stringify(data));
    }
}