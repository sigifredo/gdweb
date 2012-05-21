/*  
 *  hello-1.c - The simplest kernel module.
 */

#include<linux/kernel.h>
#include<linux/init.h>

static int __init hello_2_init(void)
{
    printk(KERN_INFO "Leyendo página web\n");

    return 0;
}

static void __exit hello_2_exit(void)
{
    printk(KERN_INFO "adios página web\n");
}

module_init(hello_2_init);
module_exit(hello_2_exit);

// MODULE_AUTHOR("Autor: GfifDev");
// MODULE_LICENSE("GPL");
// MODULE_DESCRIPTION("Description");
