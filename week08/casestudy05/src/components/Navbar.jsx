import React from 'react';
import { CoffeeOutlined, HomeOutlined, IdcardOutlined } from '@ant-design/icons'
import { Menu } from 'antd'

const items = [
    {
        label: 'Home',
        key: 'home',
        icon: <HomeOutlined/>
    },
    {
        label: 'Menu',
        key: 'menu',
        icon: <CoffeeOutlined/>
    },
    {
        label: 'About',
        key: 'about',
        icon: <IdcardOutlined/>
    },
]

const Navbar = () => {
    return (
        <Menu mode="horizontal" items={items}/>    
    );
};

export default Navbar;