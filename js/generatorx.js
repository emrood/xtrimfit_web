// import FingerprintJS from 'fingerprint';

function initFingerprintJS() {
    // Initialize an agent at application startup.
    const fpPromise = FingerprintJS.load();

    // Get the visitor identifier when you need it.
    fpPromise
        .then(fp => fp.get())
        .then(result => {
            // This is the visitor identifier:
            const visitorId = result.visitorId;
            console.log(visitorId);
        })
}


