export default {
	/* Валидация почты */
	validateMail(mail) {
		let mailRegexp =
			/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

		return mailRegexp.test(mail);
	},
	/* Валидация телефона */
	validateMail(phone) {
		let phoneRegexp = /^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/;

		return phoneRegexp.test(phone);
	},
};
