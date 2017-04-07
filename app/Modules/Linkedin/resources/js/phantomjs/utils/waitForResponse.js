module.exports = async (page) => {
    return new Promise(async resolve => {
        await page.on("onLoadFinished", async () => {
            await page.off('onLoadFinished');
            resolve();
        });
    });
};