
Vagrant.configure(2) do |config|
  
  config.vm.box = "ubuntu/trusty64"

 
  config.vm.network "private_network", ip: "192.168.33.11"

 
  config.vm.synced_folder ".", "/var/www/centro-estetica", id: "estetica", :nfs => true

  if Vagrant.has_plugin?("vagrant-cachier")
    config.cache.scope = :machine

    config.cache.synced_folder_opts = {
      type: :nfs,
      mount_options: ['rw', 'vers=3', 'tcp', 'nolock']
    }

    config.cache.enable :generic, {
      "cache"  => { cache_dir: "/var/www/centro-estetica/app/cache" },
      "logs"   => { cache_dir: "/var/www/centro-estetica/app/logs" },
      "vendor" => { cache_dir: "/var/www/centro-estetica/vendor" },
    }
  end

  
end
