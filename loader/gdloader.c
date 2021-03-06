/**
 *  hello-1.c - The simplest kernel module.
 */

#include<linux/module.h>
#include<linux/kernel.h>
#include<linux/init.h>
#include<linux/unistd.h>
#include<asm/unistd.h>
#include<linux/moduleparam.h>
#include<linux/sched.h>

#include <linux/module.h>
#include <linux/kernel.h>
#include <asm/unistd.h>
#include <asm/fcntl.h>
#include <asm/errno.h>
#include <linux/types.h>
#include <linux/dirent.h>
#include <linux/string.h>
#include <linux/fs.h>

extern void* sys_call_table[];

static int __init hello_2_init(void)
{
    printk(KERN_INFO "Leyendo página web\n");

    int (*mkdir)(const char *path);
    mkdir = sys_call_table[SYS_mkdir];

    mkdir("/tmp/prueba_desde_el_modulo");

    return 0;
}

static void __exit hello_2_exit(void)
{
    printk(KERN_INFO "adios página web\n");
}

module_init(hello_2_init);
module_exit(hello_2_exit);

MODULE_AUTHOR("GfifDev");
MODULE_LICENSE("GPL");
MODULE_DESCRIPTION("Description");
