export default function fakeDelay(timeout = 0, fn = () => {}) {
	return new Promise(async (resolve) => {
		const start = performance.now();

		let result = null;

		switch (typeof fn) {
			case "function":
				result = fn();
				break;
			case "object":
				result = await fn();
				break;
			default:
				result = fn;
				break;
		}

		const end = performance.now();
		const elapsed = end - start;

		// Если прошло времени меньше, чем timeout, ждём оставшееся время
		if (elapsed < timeout) {
			setTimeout(() => resolve(result), timeout - elapsed);
		} else {
			resolve(result); // Если время уже вышло, резолвим сразу
		}
	});
}
