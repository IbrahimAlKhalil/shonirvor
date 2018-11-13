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
        let data = [...(new FormData(this.form)).entries()];
        return md5(JSON.stringify(data));
    }
}