const Web3 = require('web3');
//測試rpc https://rpc.ankr.com/eth_goerli
//正式rpc https://rpc.ankr.com/eth
const rpcURL = 'https://rpc.ankr.com/eth'; // Paste the Ganache RPC Url here, e.g. http://localhost:7545
const abi = [{"inputs":[],"payable":false,"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"tokenOwner","type":"address"},{"indexed":true,"internalType":"address","name":"spender","type":"address"},{"indexed":false,"internalType":"uint256","name":"tokens","type":"uint256"}],"name":"Approval","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"internalType":"string","name":"data","type":"string"}],"name":"Log","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"from","type":"address"},{"indexed":true,"internalType":"address","name":"to","type":"address"},{"indexed":false,"internalType":"uint256","name":"tokens","type":"uint256"}],"name":"Transfer","type":"event"},{"constant":true,"inputs":[],"name":"_totalSupply","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"internalType":"address","name":"tokenOwner","type":"address"},{"internalType":"address","name":"spender","type":"address"}],"name":"allowance","outputs":[{"internalType":"uint256","name":"remaining","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"tokens","type":"uint256"}],"name":"approve","outputs":[{"internalType":"bool","name":"success","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"internalType":"address","name":"tokenOwner","type":"address"}],"name":"balanceOf","outputs":[{"internalType":"uint256","name":"balance","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"internalType":"uint8","name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"name","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"internalType":"uint256","name":"a","type":"uint256"},{"internalType":"uint256","name":"b","type":"uint256"}],"name":"safeAdd","outputs":[{"internalType":"uint256","name":"c","type":"uint256"}],"payable":false,"stateMutability":"pure","type":"function"},{"constant":true,"inputs":[{"internalType":"uint256","name":"a","type":"uint256"},{"internalType":"uint256","name":"b","type":"uint256"}],"name":"safeDiv","outputs":[{"internalType":"uint256","name":"c","type":"uint256"}],"payable":false,"stateMutability":"pure","type":"function"},{"constant":true,"inputs":[{"internalType":"uint256","name":"a","type":"uint256"},{"internalType":"uint256","name":"b","type":"uint256"}],"name":"safeMul","outputs":[{"internalType":"uint256","name":"c","type":"uint256"}],"payable":false,"stateMutability":"pure","type":"function"},{"constant":true,"inputs":[{"internalType":"uint256","name":"a","type":"uint256"},{"internalType":"uint256","name":"b","type":"uint256"}],"name":"safeSub","outputs":[{"internalType":"uint256","name":"c","type":"uint256"}],"payable":false,"stateMutability":"pure","type":"function"},{"constant":true,"inputs":[],"name":"symbol","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"totalSupply","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"to","type":"address"},{"internalType":"uint256","name":"tokens","type":"uint256"}],"name":"transfer","outputs":[{"internalType":"bool","name":"success","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"from","type":"address"},{"internalType":"address","name":"to","type":"address"},{"internalType":"uint256","name":"tokens","type":"uint256"}],"name":"transferFrom","outputs":[{"internalType":"bool","name":"success","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"to","type":"address"},{"internalType":"uint256","name":"tokens","type":"uint256"},{"internalType":"string","name":"source","type":"string"}],"name":"transferLogData","outputs":[{"internalType":"bool","name":"success","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"}]

const web3 = new Web3(rpcURL);

//Smart contract Address
// Test contract 0x2Ef17e2cf7467D2d94d80a51D9ED8808CA19C75F
// Mainnet contract 0xDe539E156e12b9a1761fC7E5D9F7De6A6f2598e5
const address = '0xDe539E156e12b9a1761fC7E5D9F7De6A6f2598e5';

// Test owner 0x8AcD9d5843485Bc7Ebb3B1d59F16b417B48170F4
// Mainnet owner 0x41DFb9246279a2B25dfe2DB9Beb1cBa3b7EA1Bb3
//Contract creation address
const owner    = '0x41DFb9246279a2B25dfe2DB9Beb1cBa3b7EA1Bb3' // Paste the 1st account from Ganache here

//owner的Pk
// Test owner PK 6c80e44e92fdced97bb13feb3914b165f37062c68d60ca079e4d108b18562a81
// owner PK 
const privatekey = '';

// Receiver Address
var receiverAddress = '' // Paste the 2nd account from Ganache here
var receiverAddress = '' // Paste the 2nd account from Ganache here
// Log data
var logData = '';
// Current lowest gas price
var lowGasPrice = 0
// Maximum gas limit willing to spend, default: 120000
const gasLimit = 120000;
const gasLimit = 120000
//token amount
const transferAmount = 0.01;

var responeData = {
	'hash':'',
	'success': true,
	'message': ''
}

const getAddress = new Promise ((resolve, reject)=>{
	//Get the target address through command parameters. (--address is a required parameter)
	const customIndex = process.argv.indexOf('--address');
	let receiverAddressTemp;

	if (customIndex > -1) {
		receiverAddressTemp = process.argv[customIndex + 1];
	}else{
		console.error('Target address not found');
		reject({success: false,'message':'Target address not found'})
	}
	// Target address
	receiverAddress = (receiverAddressTemp || 'Default');
	resolve(receiverAddress)
})

const getLogData =  new Promise ((resolve, reject)=>{
	//Get the Log Data through command parameters. (--data is a required parameter)
	const logDataIndex = process.argv.indexOf('--data');
	let logDataTemp;

	if (logDataIndex > -1) {
		logDataTemp = process.argv[logDataIndex + 1];
	}else{
		console.error('Please enter the Log Data');
		reject({success: false, 'message': 'Please enter the Log Data'});
	}

	// Target address
	logData = (logDataTemp || 'Default');
	resolve(logData)
})

const getLowGasPrice =  new Promise ((resolve, reject)=>{
	//Get the lowGasPrice data through command parameters. (--gasPrice is a required parameter)
	const lowGasPriceIndex = process.argv.indexOf('--gasPrice');
	let lowGasPriceTemp;
	if (lowGasPriceIndex > -1) {
		lowGasPriceTemp = process.argv[lowGasPriceIndex + 1];
	}else{
		console.error('Please enter the lowGasPrice');
		reject({success: false,'message':'Please enter the lowGasPrice'});
	}
	
	// Target lowGasPrice
	lowGasPrice = (lowGasPriceTemp || 0);
	resolve(lowGasPrice)
	const lowGasPriceIndex = process.argv.indexOf('--gasPrice');
	let lowGasPriceTemp;
	if (lowGasPriceIndex > -1) {
		lowGasPriceTemp = process.argv[lowGasPriceIndex + 1];
	}else{
		console.error('Please enter the Log Data');
		reject({success: false, 'message': 'Please enter the lowGasPrice'});
	}
	
	// Target address
	lowGasPrice = (lowGasPriceTemp || 0);
	resolve(lowGasPrice)
})

// Execute after getting three parameters
Promise.all([getAddress,getLogData,getLowGasPrice]).then((res)=>{
	
	run().then((res)=>{
			responeData = res;
			respone();
		}).catch((err)=>{
			responeData = err;
			respone();
		}
	);
}).catch((err)=>{
	responeData = err;
	respone();
});

function run(){
	return new Promise(async (resolve, reject) => {
		try {
			//get nonce
			const nonce = await web3.eth.getTransactionCount(owner, "latest");
			//convert Eth to wei
			const value = web3.utils.toWei(transferAmount.toString());
			
			const tokenContract = new web3.eth.Contract(abi, address);
	
	
			//TODO:error handler
			const data  = tokenContract.methods.transferLogData(receiverAddress, value,logData).encodeABI();
			

			const transaction = {
				'to': address,
				'gasLimit': gasLimit,
				'gasPrice': lowGasPrice,
				'nonce': nonce,
				'data': data,
			}
			
			const signTrx = await web3.eth.accounts.signTransaction(transaction, privatekey);
			
			web3.eth.sendSignedTransaction(signTrx.rawTransaction, function(error, hash){
				if(error){
					reject({
						'success': false,
						'message': error.message
					})
				} else{
					resolve({
						'hash':hash,
						'success': true,
						'message': 'transaction submitted : ' . hash
					});
				}
			})

		}catch (e) {
			resolve({
				'success': false,
				'message':  e
			});
		}
	})
}



function respone(){
	console.log(JSON.stringify(responeData));
}
