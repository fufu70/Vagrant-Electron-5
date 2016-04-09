##WubbaLubbadubdub!!!

Wanna setup [Vagrant NodeJS instead?](https://github.com/fufu70/HackStetson-Vagrant-NodeJS)

###Setting up vagrant

First download [Vagrant](https://www.vagrantup.com/downloads.html) and [Virtual Box](https://www.virtualbox.org/wiki/Downloads)

After you have installed all of those dependencies run:

> $ vagrant up

This will then provision your vagrant box.

After you have it setup you can simply read and write from the electron pins through javascript:

```javascript
Electron.readPin({
	pin: 'D6',
	callback: function(data) {
		console.log(data);
	}
})

Electron.writePin({
	pin: 'D6',
	value: Electron.constants.HIGH,
	callback: function(data) {
		console.log(data);
	}
})
```

---

###Power Down

Once your done with your vagrant box turn it off by running 

> $ vagrant halt

Or completly wipeout your box to start fresh by calling 

> $ vagrant destroy



