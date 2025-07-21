export default class TimeManager {
	constructor() {
		this._startValue = 0;
		this._endValue = 0;
	}

	start() {
		this.startValue = performance.now();
	}

	end() {
		this.endValue = performance.now();
	}

	difference(timeout = 0, func = () => {}) {
		if (this.endValue - this.startValue < timeout) {
			setTimeout(() => {
				func();
			}, timeout - (this.endValue - this.startValue));
		} else {
			func();
		}
	}
}
